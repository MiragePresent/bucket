<?php

namespace App\Services\Bucket;

use App\File;

/**
 * Class Bucket
 *
 * @author Davyd Holovii <mirage.present@gmail.com>
 * @since  02.04.2019
 */
class BucketService
{
    public const GRABAR_QUEUE_NAME = "grabar";
    public const GRABAR_JOB_CLASS = ProcessFileJob::class;
    public const BUCKET_DIRECTORY = "bucket";

    /**
     * File download handler
     *
     * @var Grabar
     */
    protected $grabar;

    public function __construct(Grabar $grabar)
    {
        $this->grabar = $grabar;
    }

    /**
     * List files inside bucket
     *
     * @return array
     */
    public function list(): array
    {
        return File::all()
            ->map(function (File $file) {
                return $file->getData();
            })->toArray();
    }

    /**
     * Add file to bucket
     *
     * @param string $resource Link to file that have to be downloaded
     *
     * @return File
     * @throws BucketException
     */
    public function add(string $resource): File
    {
        $this->validateResource($resource);

        $filename = $this->parseFileName($resource);
        $saveAs = $this->createPath($filename);

        return File::create([
            "resource" => $resource,
            "name" => $filename,
            "path" => $saveAs,
            "status" => File::STATUS_PENDING,
            "hash" => substr(sha1($filename), 0, 10),
        ]);
    }

    /**
     * Whether file has been added or not
     *
     * @param string $resource
     *
     * @return bool
     */
    public function has(string $resource): bool
    {
        return (bool) File::where("resource", $resource)->take(1)->count();
    }

    /**
     * Downloader
     *
     * @return Grabar
     */
    public function grabar(): Grabar
    {
        return $this->grabar;
    }

    /**
     * Checks if resource link is correct
     *
     * @param string $resource Resource link
     *
     * @throws BucketException
     */
    private function validateResource(string $resource): void
    {
        $validator = \Validator::make(compact("resource"), [
            "resource" => "required|url|unique:files,resource"
        ], [
            "required" => "Resource link is required",
            "url" => "Resource link has to be a valid url",
            "unique" => "Resource already exists in bucket",
        ]);

        if ($validator->fails()) {
            throw new BucketException($validator->errors()->first("resource"));
        }
    }

    /**
     * Parse file name from link
     *
     * @param string $resource File link
     *
     * @return string
     * @throws BucketException
     */
    private function parseFileName(string $resource): string
    {
        $filename = basename($resource);

        if ($filename !== $resource) {
            // if file has extension
            $split = explode(".", $filename);

            if (count($split) > 1 && array_pop($split)) {
                // check if filename is not host
                $parsed = parse_url($resource);

                if (count($parsed) !== 1 && (isset($parsed["host"]) && $parsed["host"] !== $filename)) {
                    return $filename;
                }
            }
        }

        throw new BucketException("Resource link is not path to a file");
    }

    /**
     * Creates unique path to file from name
     *
     * @param string      $filename
     * @param string|null $suffix   Suffix for making name unique
     *
     * @return string
     */
    private function createPath(string $filename, string $suffix = null): string
    {
        $split = explode(".", $filename);
        $ext = array_pop($split);

        $name = implode("_", $split);

        if (null !== $suffix) {
            $name .= "_" . $suffix;
        }

        $path = static::BUCKET_DIRECTORY . "/" . $name . "." . $ext;

        if (File::where("path", $path)->take(1)->count()) {
            return $this->createPath($filename, (string) (++$suffix));
        }

        return $path;
    }
}
