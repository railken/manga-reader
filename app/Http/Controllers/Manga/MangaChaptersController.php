<?php

namespace Api\Http\Controllers\Manga;

use Api\Http\Controllers\RestController;
use Api\Http\Controllers\Traits\RestIndexTrait;
use Api\Http\Controllers\Traits\RestShowTrait;
use Core\Chapter\ChapterManager;
use Illuminate\Http\Request;

class MangaChaptersController extends RestController
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
        'prev',
        'next',
        'resources',
        'manga.id',
        'manga.slug',
    ];

    protected static $fillable = [];

    /**
     * Construct.
     *
     * @param ChapterManager $manager
     */
    public function __construct(ChapterManager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    /**
     * Find one by identifier.
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
     * Create a new instance for query.
     *
     * @return QueryBuilder
     */
    public function getQuery()
    {
        return $this->manager->repository->getQuery();
    }

    /**
     * Display resources.
     *
     * @param $manga_key
     * @param Request $request
     *
     * @return response
     */
    public function index($manga_key, Request $request)
    {
        $pc = new ChaptersController($this->manager);
        $query = $request->input('query')
            ? "(manga.id eq {$manga_key} or manga.slug eq {$manga_key}) and (".$request->input('query').')'
            : "manga.id eq {$manga_key} or manga.slug eq {$manga_key}";

        $request->request->add(['query' => $query]);

        return $pc->index($request);
    }

    /**
     * Get single chapter by manga and number.
     *
     * @param string  $key
     * @param string  $number
     * @param Request $request
     *
     * @return Response
     */
    public function show($key, $number, Request $request)
    {
        $resource = $this->manager->repository->getQuery()
            ->leftJoin('manga as manga', 'manga.id', '=', 'chapters.manga_id')
            ->where(function ($q) use ($key) {
                return $q->orWhere('manga.id', $key)->orWhere('manga.slug', $key);
            })
            ->where('chapters.number', $number)
            ->select('chapters.*')
            ->first();

        if (!$resource) {
            return $this->not_found();
        }

        return $this->success([
            'resource' => $this->manager->serializer->serialize($resource, $this->keys->selectable)->all(),
        ]);
    }
}
