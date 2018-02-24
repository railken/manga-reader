<?php

namespace Api\Http\Controllers\Manga;

use Api\Helper\Paginator;
use Api\Helper\Filter;
use Api\Helper\Sorter;
use Api\Http\Controllers\RestController;
use Core\Manga\MangaManager;
use Illuminate\Http\Request;
use Api\Http\Controllers\Traits\RestIndexTrait;

class LibraryController extends RestController
{
    use RestIndexTrait;

    protected static $fillable = [];

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
        'follow',
        'genres',
        'cover',
        'released_year',
        'created_at',
        'updated_at',
    ];

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
     * Create a new instance for query
     *
     * @return QueryBuilder
     */
    public function getQuery()
    {
        return $this->getUser()->library();
    }

    /**
     * Add a manga
     *
     * @param mixed $key
     * @param Request $request
     *
     * @return response
     */
    public function addManga($key, Request $request)
    {
        $user = $this->getUser();
        $manga = $this->manager->repository->findOneByIdOrSlug($key);

        if (!$manga) {
            return $this->error(['code' => 'LIBRARY_MANGA_NOT_FOUND', 'message' => 'manga not found']);
        }

        if ($user->hasMangaInLibrary($manga)) {
            return $this->error(['code' => 'LIBRARY_MANGA_ALREADY_IN', 'message' => 'manga already in the library']);
        }

        $user->library()->attach($manga);

        return $this->success(['code' => 'LIBRARY_MANGA_ADDED', 'message' => 'manga added to the library']);
    }

    /**
     * Remove a manga
     *
     * @param mixed $key
     * @param Request $request
     *
     * @return response
     */
    public function removeManga($key, Request $request)
    {
        $user = $this->getUser();
        $manga = $this->manager->repository->findOneByIdOrSlug($key);

        if (!$manga) {
            return $this->error(['code' => 'LIBRARY_MANGA_NOT_FOUND', 'message' => 'manga not found']);
        }

        if (!$user->hasMangaInLibrary($manga)) {
            return $this->error(['code' => 'LIBRARY_MANGA_ALREADY_NOT_IN', 'message' => 'manga already not in the library']);
        }

        $user->library()->detach($manga);

        return $this->success(['code' => 'LIBRARY_MANGA_REMOVED', 'message' => 'manga removed from the library']);
    }



    /**
     * Show info a manga
     *
     * @param mixed $key
     * @param Request $request
     *
     * @return response
     */
    public function showManga($key, Request $request)
    {
        $user = $this->getUser();
        $manga = $this->manager->repository->findOneByIdOrSlug($key);

        return $user->hasMangaInLibrary($manga)
            ? $this->success()
            : $this->error();
    }
}
