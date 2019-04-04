<?php

namespace App\Console\Commands;

use App\Services\Bucket\Bucket;
use App\Services\Bucket\BucketException;
use Illuminate\Console\Command;

class BucketAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bucket:add {--resource=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add file to bucket';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $resource = (string) $this->option("resource");

        if (!$resource) {
            $this->error("Option [--resource] is required");

            return;
        }

        try {
            Bucket::add($resource);
        } catch (BucketException $e) {
            $this->error($e->getMessage());

            return;
        }

        $this->info("File successfully added to bucket!");
    }
}
