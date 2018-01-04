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

class SyncChaptersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $manga;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Manga $manga)
    {
        $this->manga = $manga;
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
        $manga = $this->mangafox
        ->resource($this->manga->mangafox_uid)
        ->get();

        foreach ($manga->volumes as $volume) {
            foreach ($volume->chapters as $mangafox_chapter) {

                $chapter = $this->manager->findOneBy(['manga_id' => $this->manga->id, 'number' => $mangafox_chapter->number]);

                if (!$chapter) {

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

        // Delete chapter not detected
        // $entity = $entity->getResource();



    }

}