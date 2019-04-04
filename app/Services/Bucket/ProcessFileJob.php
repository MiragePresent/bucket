<?php

namespace App\Services\Bucket;

use App\File;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class ProcessFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * File for processing
     *
     * @var File
     */
    private $file;

    /**
     * Create a new job instance.
     *
     * @param File $file File for processing
     * @return void
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $update = ["status" => File::STATUS_ERROR];

        try {
            $this->file->update(["status" => File::STATUS_PROCESSING]);

            if (Bucket::grabar()->downloadAs($this->file->resource, $this->file->path)) {
                $update["status"] = File::STATUS_COMPLETE;
                $update["size"] = Storage::size($this->file->path);
            }
        } finally {
            $this->file->update($update);
        }
    }
}
