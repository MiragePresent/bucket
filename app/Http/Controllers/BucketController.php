<?php

namespace App\Http\Controllers;

use App\File;
use App\Services\Bucket\Bucket;
use App\Services\Bucket\BucketException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ViewErrorBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BucketController extends Controller
{
    public function index()
    {
        return view("bucket.all", [
            "files" => Bucket::list()
        ]);
    }

    public function add(Request $request)
    {
        try {
            Bucket::add((string) $request->get("resource"));
        } catch (BucketException $e) {
            $errors = new ViewErrorBag();
            $errors->add("resource", $e->getMessage());
            return redirect()->back()->withErrors(["resource" => $e->getMessage()])->withInput();
        }

        return redirect()->back()->with("success", "File added to bucket");
    }

    public function download(string $path)
    {
        /** @var File $file */
        $file = File::where("path", $path)->first();

        if (empty($file)) {
            return abort(404);
        }

        set_time_limit(0);

        /** @var \League\Flysystem\Filesystem $fs */
        $fs = Storage::disk('local')->getDriver();

        $headers = [
            'Content-Type' => $fs->getMimetype($file->path),
            "Content-Length" => $fs->getSize($file->path),
            'Content-disposition' => 'attachment; filename="' . $file->name . '"',
        ];

        // !!! FOR LOCAL DRIVER !!!
        $root = $fs->getAdapter()->getPathPrefix();

        return new BinaryFileResponse($root . $file->path, 200, $headers);
    }

    public function shortLink(string $hash)
    {
        /** @var File $file */
        $file = File::whereHash($hash)->first();

        if (empty($file)) {
            return abort(404);
        }

        return redirect()->route("bucket.file", ["path" => $file->path]);
    }
}
