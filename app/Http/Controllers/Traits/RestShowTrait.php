<?php

namespace Api\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait RestShowTrait
{
    /**
     * Display a resource.
     *
     * @param mixed   $key
     * @param Request $request
     *
     * @return response
     */
    public function show($key, Request $request)
    {
        $resource = $this->findOneByIdentifier($key);

        if (!$resource) {
            return $this->not_found();
        }

        return $this->success([
            'resource' => $this->manager->serializer->serialize($resource, $this->keys->selectable)->all(),
        ]);
    }
}
