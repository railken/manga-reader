<?php

namespace App\Scrapers;

use Closure;
use Railken\Mangadex\MangadexApi;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Railken\Bag;

class MangadexScraper implements ScraperContract
{
    /**
     * @var \Railken\Mangadex\Mangadex
     */
    protected $api;

    /**
     * @var \Symfony\Component\Cache\Simple\FilesystemCache
     */
    protected $cache;

    /**
     * Create a new instance.
     */
    public function __construct()
    {
        $this->api = new MangadexApi();
        $this->cache =  new FilesystemCache('MangadexScraper', 3600);
    }


    /**
     * Retrieve name.
     *
     * @return string
     */
    public function getName()
    {
        return 'mangadex';
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return 2;
    }

    /**
     * @param Closure $callback
     */
    public function index(Closure $callback)
    {
        $page = 1;

        do {
            $result = $this->api->search()->page($page)->get();
            $pages = $result->pages;
            ++$page;

            foreach ($result->results as $result) {
                $callback($result);
            }
        } while ($page < $pages);
    }

    /**
     * @param string $uid
     */
    public function get(string $uid)
    {
        return $this->api->resource($uid)->get();
    }

    /**
     * Retrieve aliases
     *
     * @param \Railken\Bag $scraperResult
     *
     * @return array
     */
    public function getAliases(Bag $scraperResult)
    {
        return array_merge([$scraperResult->name], $scraperResult->aliases);
    }
}
