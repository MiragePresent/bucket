<?php

namespace Tests\Unit;

use App\File;
use App\Services\Bucket\Bucket;
use App\Services\Bucket\Grabar;
use App\Services\Bucket\ProcessFileJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Class ProcessFileJobTest
 *
 * @author Davyd Holovii <mirage.present@gmail.com>
 * @since  03.04.2019
 */
class ProcessFileJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_handing_job_with_correct_input()
    {
        Storage::fake();
        $grabar = $this->getMockBuilder(Grabar::class)
            ->setMethods(["downloadAs"])
            ->getMock();
        $grabar->method("downloadAs")->willReturn(true);

        Storage::shouldReceive("size")->andReturn(1024);
        Bucket::shouldReceive("grabar")->andReturn($grabar);

        /** @var File $file */
        $file = factory(File::class)->create([
            "status" => File::STATUS_PENDING,
            "size" => null,
        ]);

        $job = new ProcessFileJob($file);

        $job->handle();
        $file->refresh();

        static::assertEquals(File::STATUS_COMPLETE, $file->status);
        static::assertNotEmpty($file->size);
    }

    public function test_handing_job_with_incorrect_input()
    {
        $grabar = $this->getMockBuilder(Grabar::class)
            ->setMethods(["downloadAs"])
            ->getMock();
        $grabar->method("downloadAs")->willReturn(false);
        Bucket::shouldReceive("grabar")->andReturn($grabar);

        /** @var File $file */
        $file = factory(File::class)->create([
            "status" => File::STATUS_PENDING,
            "size" => null,
        ]);

        $job = new ProcessFileJob($file);

        $job->handle();
        $file->refresh();

        static::assertEquals(File::STATUS_ERROR, $file->status);
        static::assertEmpty($file->size);
    }
}
