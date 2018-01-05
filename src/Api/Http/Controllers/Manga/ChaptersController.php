<?php

namespace Api\Http\Controllers\Manga;

use Api\Http\Controllers\Traits\RestIndexTrait;
use Api\Http\Controllers\Traits\RestShowTrait;
use Api\Http\Controllers\RestController;
use Core\Chapter\ChapterManager;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class ChaptersController extends RestController
{
    use RestIndexTrait;
    use RestShowTrait;

    protected static $query = [
        'id',
        'volume',
        'number',
        'title',
        'released_at',
        'created_at',
        'updated_at',
        'scans',
        'resources',
        'manga.id',
        'manga.slug'
    ];


    protected static $fillable = [];


    /**
     * Construct
     *
     * @param ChapterManager $manager
     */
    public function __construct(ChapterManager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    /**
     * Find one by identifier
     *
     * @param mixed $key
     *
     * @return Chapter
     */
    public function findOneByIdentifier($key)
    {
        return $this->manager->repository->findOneByIdOrSlug($key);
    }
    
    /**
     * Create a new instance for query
     *
     * @return QueryBuilder
     */
    public function getQuery()
    {
        return $this->manager->repository->getQuery()->leftJoin('manga as manga', 'manga.id', '=', 'chapters.manga_id')->select('chapters.*');
    }
}
