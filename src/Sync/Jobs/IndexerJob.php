<?php

namespace Sync\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Railken\Mangafox\Mangafox;
use Core\Manga\MangaManager;

class IndexerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


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

        $manager = new MangaManager();
        $list = (new Mangafox())->index()->get();

        $results = $list->results;


        foreach ($results as $result) {

            $response = $manager->updateOrCreate(['mangafox_uid' => $result->uid], [
                'title' => $result->name,
                'mangafox_url' => $result->url,
                'mangafox_uid' => $result->uid,
                'mangafox_id' => $result->id
            ]);
        }

    }
}