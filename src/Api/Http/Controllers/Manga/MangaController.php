<?php

namespace Api\Http\Controllers\Manga;

use Api\Http\Controllers\Traits\RestIndexTrait;
use Api\Http\Controllers\Traits\RestShowTrait;
use Api\Http\Controllers\RestController;
use Core\Manga\MangaManager;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Railken\Laravel\ApiHelpers\Paginator;

class MangaController extends RestController
{
    use RestIndexTrait;
    use RestShowTrait;

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
        'last_chapter_released_at',
        'last_chapter',
        'created_at',
        'updated_at',
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
     * Find one by identifier
     *
     * @param mixed $key
     *
     * @return Manga
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
        return $this->manager->repository->getQuery();
    }


    /**
     * Follow manga chapters
     *
     * @param mixed $key
     * @param Request $request
     *
     * @return Response
     */
    public function request($key, Request $request)
    {
        $manga = $this->findOneByIdentifier($key);

        if (!$manga) {
            abort(404);
        }

        $this->manager->follow($manga);

        return $this->success(['message' => 'ok']);
    }



    /**
     * Retrieve releases
     *
     * @param Request $request
     *
     * @return Response
     */
    public function releases(Request $request)
    {
        $query = $this->getManager()->getRepository()->newQueryLastReleased();

        # Pagination
        $paginator = new Paginator();
        $paginator = $paginator->paginate($query->count(), $request->input('page', 1), $request->input('show', 10));

        $resources = $query
            ->skip($paginator->get('skip'))
            ->take($paginator->get('take'))
            // ->select($selectable->toArray())
            ->get();

        $select = $this->keys->selectable;

        $response = $this->success([
            'resources' => $resources->map(function ($record) use ($select) {
                $manga = $this->serialize($record, $select);

                // $manga['last_chapter'] = (new \Core\Chapter\ChapterManager())->serializer->serialize($manga->last_chapter);
                return $manga;
            }),
            'select' => $select->values(),
            'pagination' => $paginator->all(),
        ]);

        return $response;
    }
}
