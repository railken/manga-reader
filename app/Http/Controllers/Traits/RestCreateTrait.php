<?php

namespace Api\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Api\Helper\Paginator;
use Api\Helper\Filter;
use Api\Helper\Sorter;

use Api\Helper\Exceptions\FilterSyntaxException;

trait RestCreateTrait
{

    /**
     * Create a new resource
     *
     * @param Request $request
     *
     * @return response
     */
    public function create(Request $request)
    {
        $manager = $this->manager;

        $result = $manager->create($request->only($this->keys->fillable));

        return $result->ok()
            ? $this->success(['resource' => $manager->serializer->serialize($result->getResource(), $this->keys->selectable)->all()])
            : $this->error(['errors' => $result->getSimpleErrors()]);
    }
}
