<?php

namespace App\Jobs;

use App\Scrapers\KissmangaScraper;
use App\Scrapers\MangafoxScraper;

trait ScraperProviders
{
    public function getScrapers()
    {
        return [
            'kissmanga' => new KissmangaScraper(),
        ];
    }
}
