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

                try {
                    $mangaManager = new MangaManager();
                    $sourceManager = new SourceManager();

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

                        $this->handleManga($manga, $scraperResult);
                        $this->handleAliases($manga, $scraperResult);
                        $this->handleTags($manga, $scraperResult);
                        $this->handleCover($manga, $scraperResult);
                    }
                } catch (\Exception $e) {
                    Log::error(sprintf("An error has occurred while saving %s:%s", $scraper->getName(), $scraperResult->uid));

                    throw $e;
                }
            });
        }
    }

    /**
     * @param Manga $manga
     * @param \Railken\Bag $scraperResult
     */
    public function handleAliases(Manga $manga, Bag $scraperResult)
    {
        $aliasManager = new AliasManager();

        foreach (array_merge([$scraperResult->name], $scraperResult->aliases) as $alias) {
            $aliasManager->updateOrCreateOrFail([
                'name' => $alias,
                'aliasable_type' => Manga::class,
                'aliasable_id'   => $manga->id,
            ]);
        }
    }

    /**
     * @param Manga $manga
     * @param \Railken\Bag $scraperResult
     */
    public function handleTags(Manga $manga, Bag $scraperResult)
    {
        $tagManager = new TagManager();
        $tagEntityManager = new TagEntityManager();

        foreach ($scraperResult->genres as $genre) {

            $genreTag = $tagManager->findOrCreateOrFail([
                'name' => 'genre'
            ])->getResource();

            $tag = $tagManager->findOrCreateOrFail([
                'name' => $genre,
                'parent_id' => $genreTag->id
            ])->getResource();

            $tagEntityManager->updateOrCreateOrFail([
                'tag_id' => $tag->id,
                'taggable_type' => Manga::class,
                'taggable_id'   => $manga->id,
            ]);
        }

    }

    /**
     * @param Manga $manga
     * @param \Railken\Bag $scraperResult
     */
    public function handleManga(Manga $manga, Bag $scraperResult)
    {
        $mangaManager = new MangaManager();

        $result = $mangaManager->update($manga, [
            'status' => strtolower($scraperResult->status),
            'description' => $scraperResult->description,
        ]);
    }

    /**
     * @param Manga $manga
     * @param \Railken\Bag $scraperResult
     */
    public function handleCover(Manga $manga, Bag $scraperResult)
    {
        dispatch((new \App\Jobs\DownloadCover($manga->id, $scraperResult->cover))->onQueue('sync.index'));
    }
}
