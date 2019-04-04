<?php

namespace App\Services\Bucket;

use App\File;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

class FileObserver
{
    /**
     * Handle the file "created" event.
     *
     * @param  \App\File  $file
     * @return void
     */
    public function created(File $file)
    {
        $jobClass = BucketService::GRABAR_JOB_CLASS;
        Queue::pushOn(BucketService::GRABAR_QUEUE_NAME, new $jobClass($file));
    }

    /**
     * Handle the file "deleted" event.
     *
     * @param  \App\File  $file
     * @return void
     */
    public function deleted(File $file)
    {
        if (Storage::exists($file->path)) {
            Storage::delete([$file->path]);
        }
    }


    /**
     * Handle the file "force deleted" event.
     *
     * @param  \App\File  $file
     * @return void
     */
    public function forceDeleted(File $file)
    {
        $this->deleted($file);
    }
}
