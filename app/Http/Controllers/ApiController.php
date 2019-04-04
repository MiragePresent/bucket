<?php

namespace App\Http\Controllers;

use App\File;
use App\Services\Bucket\Bucket;
use App\Services\Bucket\BucketException;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function add(Request $request)
    {
        try {
            /** @var File $file */
            $file = Bucket::add((string) $request->resource);
        } catch (BucketException $e) {
            return response()->json(["errors" => ["resource" => $e->getMessage()]], 400);
        }

        return response()->json($file->getData(), 201);
    }

    public function list()
    {
        return response()->json(Bucket::list());
    }

    public function info(File $file)
    {
        return response()->json($file->getData());
    }
}
