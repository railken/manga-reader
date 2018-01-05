<?php

namespace Sync\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Railken\Mangafox\Mangafox;
use Core\Manga\MangaManager;
use Core\Manga\Manga;
use Illuminate\Support\Facades\Storage;
use Cocur\Slugify\Slugify;

class SyncReleasesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pages;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pages)
    {
        $this->pages = $pages;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->mangafox = new Mangafox();
        $this->manager = new MangaManager();

        for ($page = 1; $page <= $this->pages; $page++) {
            $results = $this->mangafox->releases()->page($page)->get();

            foreach ($results->results as $manga_raw) {
                $uid = $manga_raw->uid;
                $manga = $this->manager->repository->findOneBy(['mangafox_uid' => $uid]);

                // No Manga? 
                if (!$manga) {
                    dispatch((new IndexMangaJob($uid))->onQueue("sync.index"));
                }

                $chapter = collect($manga_raw->chapters)->sortByDesc(function($chapter) {
                    return new \DateTime($chapter->released_at);
                })->first();

                $last_chapter_released_at = new \DateTime($chapter->released_at);

                if ($manga && $manga->follow && $manga->last_chapter_released_at < $last_chapter_released_at) {

                    dispatch((new SyncChaptersJob($manga))->onQueue("sync.index"));
                    $manga->synced_at = new \DateTime();
                    $manga->last_chapter_released_at = $last_chapter_released_at;
                    $manga->save();
                }
            }
        }

        // Delete chapter not detected
        // $entity = $entity->getResource();



    }

}