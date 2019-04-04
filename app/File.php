<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class File
 *
 * @property-read int $id          File id
 * @property string   $name        File name (with ext)
 * @property string   $resource    Path to resource file
 * @property string   $path        Internal file path
 * @property string   $status_name File status name
 * @property string   $hash        Hash for short links
 * @property int      $size        File size in bites
 * @property int      $status      File state status
 *
 * Relations
 * @property-read Task[] File tasks (uploading, downloading)
 */
class File extends Model
{
    // File state statuses
    public const STATUS_ERROR = -1;
    public const STATUS_PENDING = 0;
    public const STATUS_PROCESSING = 1;
    public const STATUS_COMPLETE = 2;

    /**
     * Timestamps fields are disabled
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        "name",
        "resource",
        "path",
        "size",
        "hash",
        "status",
    ];

    public function getStatusNameAttribute(): string
    {
        $names = [
            -1 => "Error",
            0  => "Pending",
            1  => "Downloading",
            2  => "Complete",
        ];

        return $names[$this->status];
    }

    public function getData(): array
    {
        $data = [
            "id" => $this->id,
            "hash" => $this->hash,
            "name" => $this->name,
            "resource" => $this->resource,
            "status" => $this->status_name,
            "downloadable" => $this->status === static::STATUS_COMPLETE,
        ];

        if ($this->status === static::STATUS_COMPLETE) {
            $data["link"] = route("bucket.file", ["path" => $this->path]);
        }

        return $data;
    }
}
