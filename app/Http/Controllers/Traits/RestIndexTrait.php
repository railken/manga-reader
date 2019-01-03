<?php

namespace Api\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Railken\ApiHelpers\Exceptions\InvalidSorterDirectionException;
use Railken\ApiHelpers\Exceptions\InvalidSorterFieldException;
use Railken\Laravel\ApiHelpers\Filter;
use Railken\Laravel\ApiHelpers\Paginator;
use Railken\Laravel\ApiHelpers\Sorter;
use Railken\SQ\Exceptions\QuerySyntaxException;

trait RestIndexTrait
{
    /**
     * Display resources.
     *
     * @param Request $request
     *
     * @return response
     */
    public function index(Request $request)
    {
        return $this->createIndexResponseByQuery($this->getQuery(), $request);
    }

    public function createIndexResponseByQuery($query, Request $request)
    {
        // FilterSyntaxException
        try {
            $filter = new Filter();

            if ($request->input('query')) {
                $filter->setKeys($this->keys->query);
                $filter->setParseKey(function ($key) {
                    return $this->parseKey($key);
                });
                $filter->build($query, $request->input('query'));
            }
        } catch (QuerySyntaxException $e) {
            return $this->error(['code' => 'QUERY_SYNTAX_ERROR', 'message' => 'syntax error detected in filter']);
        }

        // Sorter
        $sort = new Sorter();
        $sort->setKeys($this->keys->sortable->toArray());

        // Check if sort field has
        try {
            $sort->add($request->input('sort_field', 'id'), $request->input('sort_direction', 'desc'));
        } catch (InvalidSorterDirectionException $e) {
            return $this->error(['code' => 'SORT_DIRECTION_NOT_VALID', 'message' => $e->getMessage()]);
        } catch (InvalidSorterFieldException $e) {
            return $this->error(['code' => 'SORT_PARAMETER_NOT_VALID', 'message' => $e->getMessage()]);
        }

        foreach ($sort->get() as $attribute) {
            $query->orderBy($this->parseKey($attribute->getName()), $attribute->getDirection());
        }

        // Select
        $select = collect(explode(',', $request->input('select', '')));

        $select->count() > 0 &&
            $select = $this->keys->selectable->filter(function ($attr) use ($select) {
                return $select->contains($attr);
            });

        $select->count() == 0 &&
            $select = $this->keys->selectable;

        $selectable = $select
            ->map(function ($key) {
                return $this->parseKey($key);
            });

        // Pagination
        $paginator = new Paginator();
        $paginator = $paginator->paginate($query->count(), $request->input('page', 1), $request->input('show', 10));

        $resources = $query
            ->skip($paginator->get('skip'))
            ->take($paginator->get('take'))
            // ->select($selectable->toArray())
            ->get();

        $response = $this->success([
            'resources' => $resources->map(function ($record) use ($select) {
                return $this->serialize($record, $select);
            }),
            'select'     => $select->values(),
            'pagination' => $paginator->all(),
            'sort'       => $sort,
            'filter'     => $filter,
        ]);

        // print_r(\DB::getQueryLog());
        return $response;
    }

    public function assignArrayByPath(&$arr, $path, $value, $separator = '.')
    {
        $keys = explode($separator, $path);

        foreach ($keys as $key) {
            $arr = &$arr[$key];
        }

        $arr = $value;
    }
}
