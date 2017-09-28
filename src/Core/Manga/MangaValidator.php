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
     * Validate name
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateName(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        !$entity->exists && !$parameters->exists('name') &&
            $errors->push(new Exceptions\MangaNameNotDefinedException($parameters->get('name')));

        $parameters->exists('name') &&
            !(v::length(1,255)->validate($parameters->get('name'))) &&
            $errors->push(new Exceptions\MangaNameNotValidException($parameters->get('name')));


        return $errors;
    }

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

        !$entity->exists && !$parameters->exists('aliases') &&
            $errors->push(new Exceptions\MangaAliasesNotDefinedException($parameters->get('aliases')));

        $parameters->exists('aliases') &&
            !(v::length(1,255)->validate($parameters->get('aliases'))) &&
            $errors->push(new Exceptions\MangaAliasesNotValidException($parameters->get('aliases')));


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

        !$entity->exists && !$parameters->exists('status') &&
            $errors->push(new Exceptions\MangaStatusNotDefinedException($parameters->get('status')));

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

        !$entity->exists && !$parameters->exists('overview') &&
            $errors->push(new Exceptions\MangaOverviewNotDefinedException($parameters->get('overview')));

        $parameters->exists('overview') &&
            !(v::length(1,255)->validate($parameters->get('overview'))) &&
            $errors->push(new Exceptions\MangaOverviewNotValidException($parameters->get('overview')));


        return $errors;
    }
}