<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Railken\Amethyst\Managers\MangaManager;
use Railken\Amethyst\Managers\SourceManager;
use Railken\Amethyst\Managers\FileManager;
use Railken\Amethyst\Managers\AliasManager;
use Railken\Amethyst\Managers\TagManager;
use Railken\Amethyst\Managers\TagEntityManager;
use Railken\Amethyst\Models\Manga;
use Railken\Bag;
use Illuminate\Support\Facades\Log;
use App\Scrapers\ScraperContract;
use Exception;

class IndexerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ScraperProviders;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $scrapers = $this->getScrapers();

        foreach ($scrapers as $scraper) {
            $scraper->index(function ($scraperResult) use ($scraper) {
                $mangaManager = new MangaManager();
                $sourceManager = new SourceManager();

                $source = $sourceManager->getRepository()->findOneBy([
                    'vendor' => $scraper->getName(), 
                    'uid' => $scraperResult->uid
                ]);

                if (!$source) {
                    $mangaResult = $mangaManager->createOrFail([
                        'name' => $scraperResult->name,
                    ]);

                    $manga = $mangaResult->getResource();

                    $source = $sourceManager->createOrFail([
                        'vendor'          => $scraper->getName(),
                        'weight'          => $scraper->getWeight(),
                        'url'             => $scraperResult->url,
                        'uid'             => $scraperResult->uid,
                        'sourceable_type' => Manga::class,
                        'sourceable_id'   => $manga->id,
                    ])->getResource();
                } else {
                    $manga = $source->sourceable;
                }
                

                dispatch(new \App\Jobs\IndexMangaJob($manga->id, $scraper->getName(), $scraperResult->uid));
            });
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
        Log::error(sprintf(
            "An error has occurred while scanning pages. %s, Message: %s", 
            get_class($exception), 
            $exception->getMessage()
        ));
    }
}
