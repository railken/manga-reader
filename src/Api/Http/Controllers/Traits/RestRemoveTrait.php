<?php

namespace Api\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Api\Helper\Paginator;
use Api\Helper\Filter;
use Api\Helper\Sorter;

use Api\Helper\Exceptions\FilterSyntaxException;

trait RestRemoveTrait
{

    /**
     * Display a resource
     *
     * @param integer $id
     * @param Request $request
     *
     * @return response
     */
    public function remove($id, Request $request)
    {
        $resource = $this->manager->findOneBy(['id' => $id]);

        if (!$resource)
            return $this->not_found();

        $result = $this->manager->remove($resource);

        return $result->ok() 
            ? $this->success(['message' => 'Removed'])
            : $this->error(['errors' => $result->getSimpleErrors()]);
    }

}