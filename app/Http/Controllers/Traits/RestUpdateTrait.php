<?php

namespace Api\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Api\Helper\Paginator;
use Api\Helper\Filter;
use Api\Helper\Sorter;

use Api\Helper\Exceptions\FilterSyntaxException;

trait RestUpdateTrait
{

    /**
     * Display a resource
     *
     * @param integer $id
     * @param Request $request
     *
     * @return response
     */
    public function update($id, Request $request)
    {
        $resource = $this->manager->findOneBy(['id' => $id]);

        if (!$resource) {
            return $this->not_found();
        }

        $result = $this->manager->update($resource, $request->only($this->keys->fillable));

        return $result->ok()
            ? $this->success(['resource' => $this->manager->serializer->serialize($result->getResource(), $this->keys->selectable)->all()])
            : $this->error(['errors' => $result->getSimpleErrors()]);
    }
}
