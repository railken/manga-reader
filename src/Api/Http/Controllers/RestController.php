<?php

namespace Api\Http\Controllers;

use Api\Helper\Paginator;


use Railken\Laravel\Manager\ModelContract;
use Illuminate\Http\Request;
use Api\Http\Controllers\Traits\RestIndexTrait;

abstract class RestController extends Controller
{

    use RestIndexTrait;
    
    /**
     * Return a new instance of Manager
     *
     * @return UserManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Return an array rappresentation of entity
     *
     * @param ModelContract $entity
     *
     * @return array
     */
    public function serialize(ModelContract $entity)
    {
        return $this->manager->serializer->serialize($entity);
    }
    

    /**
     * Return a json response to insert
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $this->initialize($request);
        $manager = $this->getManager();

        $parameters = $request->all();
        $parameters['user'] = $this->getUser();

        $entity = $manager->create($parameters);
        
        return $this->show($entity->id, $request);
    }


    /**
     * Return a json response to get
     *
     * @param Request $request
     *
     * @return Response
    */
    public function show($id, Request $request)
    {
        $this->initialize($request);
        $manager = $this->getManager();

        $entity = $manager->find($id);

        if (empty($entity)) {
            abort(404);
        }

        return $this->success([
            'message' => 'ok',
            'data' => [
                'resources' => $this->serialize($entity)
            ]
        ]);
    }
    /**
     * Return a json response to insert
     *
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $this->initialize($request);
        $manager = $this->getManager();

        $entity = $manager->find($id);

        if (empty($entity)) {
            abort(404);
        }


        $manager->update($entity, $request->all());

        return $this->show($entity->id, $request);
    }

    /**
     * Return a json response to insert
     *
     * @Route("/{id}")
     *  @Method("DELETE")
     *
     * @param Request $request
     *
     * @return Response
    */
    public function delete($id, Request $request)
    {
        $this->initialize($request);
        $manager = $this->getManager();

        $entity = $manager->find($id);

        if (empty($entity)) {
            abort(404);
        }

        $manager->delete($entity);

        return $this->success([
            'message' => 'ok'
        ]);
    }
}
