<?php

namespace Api\Http\Controllers\Manga;

use Api\Http\Controllers\RestController;
use Illuminate\Http\Request;
use Core\Manga\MangaManager;
use stdClass;
use Api\Helper\Paginator;

class MangaController extends RestController
{

    /**
     * Construct
     *
     */
    public function __construct(MangaManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Display manga
     *
     * @param Request $request
     *
     * @return response
     */
    public function index(Request $request)
    {

        $filter = $request->input('filter');
        $filter = json_decode($filter);

        $query = $this->manager->repository->getQuery();

        $this->filter($query, $filter);

        $paginator = Paginator::retrieve($query, $request->input('page', 1), $request->input('show', 10));

        $sort = [
            'field' => strtolower($request->input('sort_field', 'id')),
            'direction' => strtolower($request->input('sort_direction', 'desc')),
        ];

        $resources = $query
            ->orderBy($sort['field'], $sort['direction'])
            ->skip($paginator->getFirstResult())
            ->take($paginator->getMaxResults())
            ->get();

        return $this->success([
            'resources' => $resources->map(function($record) {
                return $this->manager->serializer->serialize($record);
            }),
            'pagination' => $paginator,
            'sort' => $sort,
            'filter' => $filter
        ]);
    }

}
