<?php

namespace Core\Manga;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ParameterBag;
use Illuminate\Support\Collection;
use Core\Manga\Exceptions as Exceptions;
use Respect\Validation\Validator as v;
use Railken\Laravel\Manager\ModelValidator;


class MangaValidator extends ModelValidator
{

    /**
     * Validate title
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateTitle(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        !$entity->exists && !$parameters->exists('title') &&
            $errors->push(new Exceptions\MangaTitleNotDefinedException($parameters->get('title')));

        $parameters->exists('title') &&
            !(v::length(1,255)->validate($parameters->get('title'))) &&
            $errors->push(new Exceptions\MangaTitleNotValidException($parameters->get('title')));


        return $errors;
    }

    /**
     * Validate aliases
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateAliases(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();


        return $errors;
    }

    /**
     * Validate status
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateStatus(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        $parameters->exists('status') &&
            !(v::length(1,255)->validate($parameters->get('status'))) &&
            $errors->push(new Exceptions\MangaStatusNotValidException($parameters->get('status')));


        return $errors;
    }

    /**
     * Validate overview
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateOverview(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        return $errors;
    }

    /**
     * Validate mangafox_url
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateMangafoxUrl(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        !$entity->exists && !$parameters->exists('mangafox_url') &&
            $errors->push(new Exceptions\MangaMangafoxUrlNotDefinedException($parameters->get('mangafox_url')));


        return $errors;
    }

    /**
     * Validate mangafox_uid
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateMangafoxUid(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        !$entity->exists && !$parameters->exists('mangafox_uid') &&
            $errors->push(new Exceptions\MangaMangafoxUidNotDefinedException($parameters->get('mangafox_uid')));


        return $errors;
    }

    /**
     * Validate mangafox_id
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateMangafoxId(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();
        !$entity->exists && !$parameters->exists('mangafox_id') &&
            $errors->push(new Exceptions\MangaMangafoxIdNotDefinedException($parameters->get('mangafox_id')));


        return $errors;
    }
}