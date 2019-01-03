<?php

namespace App\Scrapers;

use Closure;
use Railken\Kissmanga\Kissmanga;
use Symfony\Component\Cache\Simple\FilesystemCache;

class KissmangaScraper implements ScraperContract
{
    /**
     * @var \Railken\Kissmanga\Kissmanga
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
        $this->api = new Kissmanga();
        $this->cache =  new FilesystemCache('KissmangaScraper', 3600);
    }


    /**
     * Retrieve name.
     *
     * @return string
     */
    public function getName()
    {
        return 'kissmanga';
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

        if ($this->cache->has('kissmanga.index')) {
            $result = $this->cache->get('kissmanga.index');
        } else {
            $result = $this->api->search()->get();
            $this->cache->set('kissmanga.index', $result);
        }

        foreach ($result->results as $result) {
            $callback($result);
        }
    }

    /**
     * @param string $uid
     */
    public function get(string $uid)
    {
        return $this->api->resource($uid)->get();
    }

    public function retrieveContentByUrl($url)
    {
        $cookies = collect($this->api->getCachedCookies()->toArray());

        $cookies = $cookies->map(function($cookie) {
            return $cookie['Name']."=".$cookie['Value'];
        })->implode("; ");

        $opts = [
            'http' => [
                'method'    => 'GET',
                'header' => [
                    sprintf('user-agent: %s', $this->api->getUserAgent()),
                    sprintf('cookie: %s', $cookies),
                ]
            ]
        ];

        $context = stream_context_create($opts);

        return file_get_contents($url, false, $context);
    }
}
