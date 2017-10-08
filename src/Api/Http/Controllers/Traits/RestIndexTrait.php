<?php

namespace Api\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Api\Helper\Paginator;
use Api\Helper\Filter;
use Api\Helper\Sorter;

trait RestIndexTrait
{

    /**
     * Display resources
     *
     * @param Request $request
     *
     * @return response
     */
    public function index(Request $request)
    {
        $query = $this->manager->repository->getQuery();

        $filter = new Filter($this->only);
        $filter->build($query, $request->input('filter'));

        $paginator = new Paginator();
        $paginator = $paginator->execute($query, $request->input('page', 1), $request->input('show', 10));

        $sort = new Sorter();
        $sort = $sort->fill($request->input('sort_field', 'id'), $request->input('sort_direction', 'desc'));

        $resources = $query
            ->orderBy($sort->get('field'), $sort->get('direction'))
            ->skip($paginator->get('first_result'))
            ->take($paginator->get('max_results'))
            ->get();


        return $this->success([
            'resources' => $resources->map(function($record) {
                return $this->manager->serializer->serialize($record);
            }),
            'pagination' => $paginator->all(),
            'sort' => $sort,
            'filter' => $filter
        ]);
    }

}