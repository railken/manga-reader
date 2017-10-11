<?php

namespace Api\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Api\Helper\Paginator;
use Api\Helper\Filter;
use Api\Helper\Sorter;

use Api\Helper\Exceptions\FilterSyntaxException;

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

        $resources = $query
            ->orderBy($sort->get('field'), $sort->get('direction'))
            ->skip($paginator->get('first_result'))
            ->take($paginator->get('max_results'))
            ->select($select->toArray())
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

}