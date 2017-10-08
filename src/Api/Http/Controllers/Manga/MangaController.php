<?php

namespace Api\Http\Controllers\Manga;

use Api\Http\Controllers\Traits\RestIndexTrait;
use Api\Http\Controllers\Controller;
use Core\Manga\MangaManager;

class MangaController extends Controller
{
    use RestIndexTrait;

    protected $only = [
        'title', 
        'overview', 
        'aliases', 
        'mangafox_url', 
        'mangafox_uid', 
        'mangafox_id', 
        'status', 
        'artist', 
        'author', 
        'aliases', 
        'genres', 
        'released_year'
    ];

    /**
     * Construct
     *
     * @param MangaManager $manager
     */
    public function __construct(MangaManager $manager)
    {
        $this->manager = $manager;
    }

}
