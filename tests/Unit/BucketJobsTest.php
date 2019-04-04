<?php

namespace Tests\Unit;

use App\Services\Bucket\Bucket;
use App\Services\Bucket\BucketService;
use App\Services\Bucket\ProcessFileJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * Class BucketJobsTest
 *
 * @author Davyd Holovii <mirage.present@gmail.com>
 * @since  03.04.2019
 */
class BucketJobsTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_creation_after_adding_file_to_bucket()
    {
        Queue::fake();

        $resource = "http://example.com/assets/images/logo.png";
        Bucket::add($resource);

        Queue::assertPushedOn(BucketService::GRABAR_QUEUE_NAME, ProcessFileJob::class);
    }
}
