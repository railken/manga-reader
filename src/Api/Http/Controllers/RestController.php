<?php

namespace Api\Http\Controllers;

use Api\Helper\Paginator;


use Railken\Laravel\Manager\ModelContract;
use Illuminate\Http\Request;

abstract class RestController extends Controller
{

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
     * Return a json response of view list
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $this->initialize($request);
        $manager = $this->getManager();

        $query = $manager->getRepository()->getQuery();

        $searches = $request->input('search', []);

        
        $query->where(function ($qb) use ($searches) {
            foreach ($searches as $name => $search) {
                $qb->where($name, $search);
            }
        });

        $query->where('user_id', $this->getUser()->id);

        $paginator = Paginator::retrieve($query, $request->input('page', 1), $request->input('show', 10));

        $sort = [
            'field' => strtolower($request->input('sort_field', 'id')),
            'direction' => strtolower($request->input('sort_direction', 'desc')),
        ];

        $results = $query
            ->orderBy($sort['field'], $sort['direction'])
            ->skip($paginator->getFirstResult())
            ->take($paginator->getMaxResults())
            ->get();


        foreach ($results as $n => $k) {
            $results[$n] = $this->serialize($k);
        }

        return $this->success([
            'message' => 'ok',
            'data' => [
                'resources' => $results,
                'pagination' => $paginator,
                'sort' => $sort,
                'search' => $searches,
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
