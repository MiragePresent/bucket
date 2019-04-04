<?php

namespace Tests\Unit;

use App\File;
use App\Services\Bucket\Bucket;
use App\Services\Bucket\Grabar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BucketTest extends TestCase
{
    use RefreshDatabase;

    public function test_positive_assertion_of_existing_file()
    {
        $this->mockBucket();

        $filename = "file.fake";
        $resource = "http://example.com/files/" . $filename;

        factory(File::class)->create([
            "resource" => $resource,
            "name" => $filename
        ]);

        static::assertTrue(Bucket::has($resource), "Failed adding file to bucket");
    }

    public function test_negative_assertion_of_existing_file()
    {
        $path = "storage/directory/file.fake";

        static::assertFalse(Bucket::has($path), "File has not been added to bucket");
    }

    /**
     * @dataProvider resourceStringsProvider
     * @param string $resource Resource file path
     * @param bool $expected Adding file into bucket status
     */
    public function test_adding_file_into_bucket(string $resource, bool $expected)
    {
        $this->mockBucket();

        try {
            Bucket::add($resource);
        } catch (\Exception $e) {
            // During adding a file bucket handler can trow exception
        } finally {
            static::assertEquals($expected, Bucket::has($resource));
        }
    }

    public function resourceStringsProvider(): array
    {
        return [
            [ "http://example.com/assets/main.css", true ],
            [ "my-site.com", false ],
            [ "", false ],
            [ "/some/file", false ],
        ];
    }

    protected function mockBucket()
    {
        Storage::fake();
        $grabar = $this->getMockBuilder(Grabar::class)
            ->setMethods(["downloadAs"])
            ->getMock();
        $grabar->method("downloadAs")->willReturn(true);

        Storage::shouldReceive("size")->andReturn(1024);
        Bucket::shouldReceive("grabar")->andReturn($grabar);
        Bucket::makePartial();
    }
}
