<?php

namespace App\Jobs;

use Core\Chapter\Chapter;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Railken\Mangafox\Mangafox;

class DownloadScanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chapter;
    protected $logger;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Chapter $chapter)
    {
        $this->chapter = $chapter;
        $this->logger = new \Core\Log\LogService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $parent = $this->logger->log('info', 'manga:sync:download', "Downloading chapter #{$this->chapter->id} for manga #{$this->chapter->manga->id} '{$this->chapter->manga->title}'");

        $mangafox = new Mangafox();
        $chapter = $this->chapter;
        $manga = $chapter->manga;

        $chapter->scans = null;
        $chapter->save();

        $mangafox->scan($chapter->manga->mangafox_uid, $chapter->volume, $chapter->number)->get()->each(function ($scan, $key) use ($manga, $chapter) {
            $ext = pathinfo(strtok($scan->scan, '?'), PATHINFO_EXTENSION);
            $key = str_pad($key, 5, '0', STR_PAD_LEFT);
            $filename = $chapter->getPathChapter()."/{$key}.{$ext}";

            try {
                Storage::disk('s3')->put($filename, file_get_contents($scan->scan));
            } catch (\Exception $e) {
            }
        });
        $chapter->scans = 1;
        $chapter->save();
    }

    /**
     * The job failed to process.
     *
     * @param Exception $exception
     *
     * @return void
     */
    public function failed(Exception $exception)
    {
        $parent = $this->logger->log('error', 'manga:sync:download', 'Error while downloading scans for a chapter', [
            'exception' => [
                'class'   => get_class($exception),
                'message' => $exception->getMessage(),
            ],
        ]);
    }
}
