<?php

namespace App\Jobs;

use App\Scrapers\MangadexScraper;
use App\Scrapers\MangafoxScraper;
use Illuminate\Support\Collection;

trait ScraperProviders
{
    public function getScrapers()
    {
        return Collection::make([
            'mangadex' => new MangadexScraper(),
            'mangafox' => new MangafoxScraper(),
        ]);
    }

    public function getScraperByName(string $name)
    {
        return $this->getScrapers()->first(function ($item) use ($name) {
            return $item->getName() === $name;
        });
    }
}
