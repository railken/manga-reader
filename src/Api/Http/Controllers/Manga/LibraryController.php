<?php

namespace Api\Http\Controllers\Manga;

use Api\Helper\Paginator;
use Api\Helper\Filter;
use Api\Helper\Sorter;
use Api\Http\Controllers\Controller;
use Core\Manga\MangaManager;
use Illuminate\Http\Request;

class LibraryController extends Controller
{

    protected $only = [
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
        'released_year'
    ];

    protected $selectable = [
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

    /**
     * Display a resource
     *
     * @param mixed $key
     * @param Request $request
     *
     * @return response
     */
    public function index(Request $request)
    {
        $query = $this->getUser()->library();

        # Filter
        try {
            $filter = new Filter($this->only);
            $filter->build($query, $request->input('query'));
        } catch (FilterSyntaxException $e) {
            return $this->error(["code" => "REQUEST_QUERY_SYNTAX_ERROR", "message" => "syntax error detected in filter"]);
        }

        # Pagination
        $paginator = new Paginator();
        $paginator = $paginator->execute($query, $request->input('page', 1), $request->input('show', 10));

        # Sorter
        $sort = new Sorter();
        $sort = $sort->fill($request->input('sort_field', 'id'), $request->input('sort_direction', 'desc'));

        # Select
        $select = collect(explode(",", $request->input("select", "")))->intersect($this->selectable);
        $select->count() == 0 && $select = collect($this->selectable);

        $table = $this->manager->repository->newEntity()->getTable();

        $resources = $query
            ->orderBy($sort->get('field'), $sort->get('direction'))
            ->skip($paginator->get('first_result'))
            ->take($paginator->get('max_results'))
            ->select($select->map(function($v) use ($table) { return $table.".".$v; })->toArray())
            ->get();


        return $this->success([
            'resources' => $resources->map(function($record) use ($select) {

                return $this->manager->serializer->serializeBrief($record)->only($select->toArray())->all();
            }),
            'pagination' => $paginator->all(),
            'sort' => $sort,
            'filter' => $filter
        ]);
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
        $resource = $this->manager->repository->findOneByIdOrSlug($key);

        if (!$resource)
            return $this->error(['code' => 'LIBRARY_MANGA_NOT_FOUND', 'message' => 'manga not found']);

        if ($user->library()->where('manga_id', $resource->id)->first())
            return $this->error(['code' => 'LIBRARY_MANGA_ALREADY_IN', 'message' => 'manga already in the library']);

        $user->library()->attach($resource);

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
        $resource = $this->manager->repository->findOneByIdOrSlug($key);

        if (!$resource)
            return $this->error(['code' => 'LIBRARY_MANGA_NOT_FOUND', 'message' => 'manga not found']);

        if (!$user->library()->where('manga_id', $resource->id)->first())
            return $this->error(['code' => 'LIBRARY_MANGA_ALREADY_NOT_IN', 'message' => 'manga already not in the library']);

        $user->library()->detach($resource);

        return $this->success(['code' => 'LIBRARY_MANGA_REMOVED', 'message' => 'manga removed from the library']);
    }
}
