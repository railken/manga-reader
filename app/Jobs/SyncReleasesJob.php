<?php

namespace App\Jobs;

use Core\Manga\Manga;
use Core\Manga\MangaManager;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Railken\Mangafox\Mangafox;

class SyncReleasesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pages;
    protected $logger;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pages)
    {
        $this->pages = $pages;
        $this->logger = new \Core\Log\LogService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $parent = $this->logger->log('info', 'manga:sync:releases', "Searching new releases for $this->pages pages");

        $this->mangafox = new Mangafox();
        $this->manager = new MangaManager();

        for ($page = 1; $page <= $this->pages; ++$page) {
            $results = $this->mangafox->releases()->page($page)->get();

            foreach ($results->results as $manga_raw) {
                $uid = $manga_raw->uid;

                $this->logger->log('info', 'manga:sync:releases:detail', "The manga '{$uid}' has been found", [], $parent);

                $uid = $manga_raw->uid;
                $manga = $this->manager->repository->findOneBy(['mangafox_uid' => $uid]);

                // No Manga?
                if (!$manga) {
                    $this->logger->log('info', 'manga:sync:releases:detail', "No manga found for '{$uid}' in database. Dispatching IndexMangaJob", [], $parent);

                    dispatch((new IndexMangaJob($uid))->onQueue('sync.index'));
                }

                $chapter = collect($manga_raw->chapters)->sortByDesc(function ($chapter) {
                    return new \DateTime($chapter->released_at);
                })->first();

                $last_chapter_released_at = new \DateTime($chapter->released_at);

                if ($manga && $manga->follow && (!$manga->last_chapter_released_at || $manga->last_chapter_released_at < $last_chapter_released_at)) {
                    $this->logger->log('info', 'manga:sync:releases:detail', "An update has been found for {$uid} #{$manga->id} in database. Dispatching IndexMangaJob", [], $parent);
                    dispatch((new SyncChaptersJob($manga))->onQueue('sync.index'));
                    $manga->synced_at = new \DateTime();
                    $manga->last_chapter_released_at = $last_chapter_released_at;
                    $manga->save();
                } else {
                    $this->logger->log('info', 'manga:sync:releases:detail', "Skipping '{$uid}'", [], $parent);
                }
            }
        }
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
        $parent = $this->logger->log('error', 'manga:sync:releases', 'Error while searching new releases', [
            'exception' => [
                'class'   => get_class($exception),
                'message' => $exception->getMessage(),
            ],
        ]);
    }
}
