<?php

namespace App\Scrapers;

use Closure;
use Railken\Mangafox\Mangafox;

class MangafoxScraper implements ScraperContract
{
    /**
     * @var \Railken\Mangafox\Mangafox
     */
    protected $api;

    /**
     * Create a new instance.
     */
    public function __construct()
    {
        $this->api = new Mangafox();
    }

    /**
     * Retrieve name.
     *
     * @return string
     */
    public function getName()
    {
        return 'mangafox';
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
            $result = $this->api->directory()->page($page)->get();
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
}
