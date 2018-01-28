<?php

namespace Sync\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Railken\Mangafox\Mangafox;
use Core\Chapter\ChapterManager;
use Core\Manga\Manga;
use Illuminate\Support\Facades\Storage;
use Cocur\Slugify\Slugify;
use Exception;

class SyncChaptersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $manga;
    protected $logger;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Manga $manga)
    {
        $this->manga = $manga;
        $this->logger = new \Core\Log\LogService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->mangafox = new Mangafox();
        $this->manager = new ChapterManager();


        $parent = $this->logger->log("info", "manga:sync:chapters", "Indexing chapters for manga #{$this->manga->id} '{$this->manga->title}'");

        $manga = $this->mangafox
        ->resource($this->manga->mangafox_uid)
        ->get();

        foreach ($manga->volumes as $volume) {
            foreach ($volume->chapters as $mangafox_chapter) {

                $chapter = $this->manager->findOneBy(['manga_id' => $this->manga->id, 'number' => $mangafox_chapter->number]);

                if (!$chapter) {


                    $parent = $this->logger->log("info", "manga:sync:chapters", "A new chapter has been found for manga #{$this->manga->id} '{$this->manga->title}': V{$mangafox_chapter->volume} C{$mangafox_chapter->number} - {$mangafox_chapter->title}");

                    $chapter = $this->manager->create([
                        'number' => $mangafox_chapter->number,
                        'volume' => $mangafox_chapter->volume,
                        'title' => $mangafox_chapter->title,
                        'slug' => '',
                        'released_at' => new \DateTime($mangafox_chapter->released_at),
                        'manga_id' => $this->manga->id
                    ]);

                    $chapter = $chapter->getResource();

                } else {

                }
                
                if ($chapter->scans === NULL)
                    dispatch((new \Sync\Jobs\DownloadScanJob($chapter))->onQueue('sync.index'));

            }
        }


        // Some chapters may be deleted/renamed during the update
        // This should be handled with log (warning)
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     *
     * @return void
     */
    public function failed(Exception $exception)
    {
        $parent = $this->logger->log("error", "manga:sync:chapters", "Error while indexing chapters for manga #{$this->manga->id} '{$this->manga->title}'", [
            'exception' => [
                'class' => get_class($exception), 
                'message' => $exception->getMessage()
            ]
        ]);
    }

}