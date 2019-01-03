<?php

namespace App\Jobs;

use App\Scrapers\MangadexScraper;
use App\Scrapers\MangafoxScraper;

trait ScraperProviders
{
    public function getScrapers()
    {
        return [
            'mangadex' => new MangadexScraper(),
            'mangafox' => new MangafoxScraper(),
        ];
    }
}
