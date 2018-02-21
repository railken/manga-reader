<?php

namespace Sync\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Railken\Mangafox\Mangafox;
use Core\Manga\MangaManager;
use Cocur\Slugify\Slugify;
use Illuminate\Support\Facades\Cache;

class IndexerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mangafox = new Mangafox();
        $manager = new MangaManager();


        $list = Cache::get('sync.manga.index', $mangafox->index()->get());

        if (empty($list)) {
            $list = $mangafox->index()->get();
        }
        Cache::put('sync.manga.index', $list, 10);

        $results = $list->results;


        foreach ($results as $result) {

            if (!$manager->findOneBy(['mangafox_uid' => $result->uid])) {

                $manager->create([
                    'title' => $result->name,
                    'slug' => (new Slugify())->slugify($result->name),
                    'mangafox_url' => $result->url,
                    'mangafox_uid' => $result->uid,
                    'mangafox_id' => $result->id
                ]);

            }

            if ($manager->getRepository()->getQuery()->where('mangafox_uid', $result->uid)->whereNull('released_year')->count() > 0) {

                dispatch((new \Sync\Jobs\IndexMangaJob($result->uid))->onQueue('sync.index'));
            }

        }

    }
}