<?php

namespace App\Jobs;

use Cocur\Slugify\Slugify;
use Exception;
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
use Illuminate\Support\Facades\Storage;
use Railken\Mangafox\Mangafox;
use Illuminate\Support\Facades\Log;
use Railken\Amethyst\Models\Manga;
use App\Scrapers\ScraperContract;
use Railken\Bag;

class IndexMangaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ScraperProviders;

    protected $mangaId;
    protected $scraperName;
    protected $sourceUid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mangaId, $scraperName, $sourceUid)
    {
        $this->scraperName = $scraperName;
        $this->mangaId = $mangaId;
        $this->sourceUid = $sourceUid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mangaManager = new MangaManager();
        $manga = $mangaManager->getRepository()->findOneById($this->mangaId);

        $scraper = $this->getScraperByName($this->scraperName);

        $scraperResult = $scraper->get($this->sourceUid);

        $this->handleManga($manga, $scraper, $scraperResult);
        $this->handleAliases($manga, $scraper, $scraperResult);
        $this->handleTags($manga, $scraper, $scraperResult);
        $this->handleCover($manga, $scraper, $scraperResult);
    }

    /***
     * @param \Railken\Amethyst\Models\Manga $manga
     * @param \App\Scrapers\ScraperContract $scraper
     * @param \Railken\Bag $scraperResult
     */
    public function handleAliases(Manga $manga, ScraperContract $scraper, Bag $scraperResult)
    {
        $aliasManager = new AliasManager();

        foreach ($scraper->getAliases($scraperResult) as $alias) {
            $aliasManager->updateOrCreateOrFail([
                'name' => $alias,
                'aliasable_type' => Manga::class,
                'aliasable_id'   => $manga->id,
            ]);
        }
    }

    /**
     * @param \Railken\Amethyst\Models\Manga $manga
     * @param \App\Scrapers\ScraperContract $scraper
     * @param \Railken\Bag $scraperResult
     */
    public function handleTags(Manga $manga, ScraperContract $scraper, Bag $scraperResult)
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
     * @param \Railken\Amethyst\Models\Manga $manga
     * @param \App\Scrapers\ScraperContract $scraper
     * @param \Railken\Bag $scraperResult
     */
    public function handleManga(Manga $manga, ScraperContract $scraper, Bag $scraperResult)
    {
        $mangaManager = new MangaManager();

        $result = $mangaManager->updateOrFail($manga, [
            'status' => strtolower($scraperResult->status),
            'description' => $scraperResult->description,
        ]);
    }

    /**
     * @param \Railken\Amethyst\Models\Manga $manga
     * @param \App\Scrapers\ScraperContract $scraper
     * @param \Railken\Bag $scraperResult
     */
    public function handleCover(Manga $manga, ScraperContract $scraper, Bag $scraperResult)
    {
        $fileManager = new FileManager();

        // Skip if cover is already downloaded
        $file = $fileManager->getRepository()->findOneBy([
            'model_type' => Manga::class,
            'model_id' => $manga->id,
            'tags' => json_encode(['cover'])
        ]);

        if (!$file) {
            dispatch(new \App\Jobs\DownloadCover($manga->id, $scraperResult->cover));
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
            "An error has occurred while saving %s. %s, Message: %s", 
            $this->sourceUid,
            get_class($exception), 
            $exception->getMessage()
        ));
    }
}
