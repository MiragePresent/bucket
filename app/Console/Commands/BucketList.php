<?php

namespace App\Console\Commands;

use App\Services\Bucket\Bucket;
use Illuminate\Console\Command;

class BucketList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bucket:list {--full-strings}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List files inside bucket';

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
        $files = Bucket::list();

        $count = count($files);

        if (!$count) {
            $this->info("Bucket is empty yet");

            return;
        }

        $this->showInfo($count);

        $full = (bool) $this->option("full-strings");

        $content = [];

        foreach ($files as $num => $file) {
            $content[] = [
                $num + 1,
                $full ? $file["name"] : $this->cutStr($file["name"], 20),
                $full ? $file["resource"] : $this->cutStr($file["resource"]),
                $file["downloadable"] ? route("short_link", ["hash" => $file["hash"]]) : "",
                $file["status"],
            ];
        }

        $this->table(["#", "Name", "Resource", "Download", "Status"], $content);

        $this->showInfo($count);

    }

    /**
     * Cut string
     *
     * @param string $link   Url for cutting
     * @param int    $length Result string length
     *
     * @return string
     */
    private function cutStr(string $link, int $length = 30): string
    {
        $strlen = strlen($link);

        if ($strlen <= $length) {
            return $link;
        }

        $length -= 5;
        $start = $end = floor($length / 2);

        if ($strlen !== $start + $end) {
            $start += 1;
        }

        return substr($link, 0, $start) . " ... " . substr($link, $strlen - $end);
    }

    private function showInfo(int $count)
    {
        $this->info("There are {$count} file(s) inside bucket");
    }
}
