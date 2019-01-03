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
use Railken\Amethyst\Models\Manga;

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
                $fileManager = new FileManager();

                if (!$sourceManager->getRepository()->findOneBy(['vendor' => $scraper->getName(), 'uid' => $scraperResult->uid])) {

                    $mangaResult = $mangaManager->createOrFail([
                        'name' => $scraperResult->name,
                    ]);

                    $manga = $mangaResult->getResource();

                    $sourceManager->createOrFail([
                        'vendor'          => $scraper->getName(),
                        'weight'          => $scraper->getWeight(),
                        'url'             => $scraperResult->url,
                        'uid'             => $scraperResult->uid,
                        'sourceable_type' => Manga::class,
                        'sourceable_id'   => $manga->id,
                    ]);

                    $scraperResult = $scraper->get($scraperResult->uid);

                    $result = $mangaManager->update($manga, [
                        'status' => strtolower($scraperResult->status),
                        'description' => $scraperResult->description,
                    ]);

                    $ext = pathinfo(strtok($scraperResult->cover, '?'), PATHINFO_EXTENSION);

                    $fileResult = $fileManager->uploadFileByContent(file_get_contents($scraperResult->cover));
                    $fileManager->assignToModel($fileResult->getResource(), $manga, ['tags' => ['cover']]);

                    usleep(500000);
                }
            });
        }
    }
}
