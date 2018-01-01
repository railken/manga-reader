<?php

namespace Api\Http\Controllers\Manga;

use Api\Http\Controllers\Traits\RestIndexTrait;
use Api\Http\Controllers\Traits\RestShowTrait;
use Api\Http\Controllers\RestController;
use Core\Manga\MangaManager;

use Illuminate\Http\Request;

class MangaController extends RestController
{
    use RestIndexTrait;
    // use RestShowTrait;

    protected static $query = [
        'id',
        'title',
        'slug',
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
        'released_year',
        'created_at',
        'updated_at'
    ];


    protected static $fillable = [];


    /**
     * Construct
     *
     * @param MangaManager $manager
     */
    public function __construct(MangaManager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    /**
     * Display a resource
     *
     * @param mixed $key
     * @param Request $request
     *
     * @return response
     */
    public function show($key, Request $request)
    {
        $resource = $this->manager->repository->findOneByIdOrSlug($key);

        if (!$resource)
            return $this->not_found();

        return $this->success([
            'resource' => $this->manager->serializer->serialize($resource)->all()
        ]);
    }

    /**
     * Create a new instance for query
     *
     * @return QueryBuilder
     */
    public function getQuery()
    {
        return $this->manager->repository->getQuery();
    }

    

}
