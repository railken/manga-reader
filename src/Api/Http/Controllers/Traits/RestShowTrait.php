<?php

namespace Api\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Api\Helper\Paginator;
use Api\Helper\Filter;
use Api\Helper\Sorter;

use Api\Helper\Exceptions\FilterSyntaxException;

trait RestShowTrait
{

    /**
     * Display a resource
     *
     * @param integer $id
     * @param Request $request
     *
     * @return response
     */
    public function show($id, Request $request)
    {
        $resource = $this->manager->findOneBy(['id' => $id]);

        if (!$resource)
            return $this->not_found();

        return $this->success([
            'resource' => $this->manager->serializer->serialize($resource)->all()
        ]);
    }

}