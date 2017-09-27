<?php

namespace Core\User;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ParameterBag;
use Illuminate\Support\Collection;
use Core\User\Exceptions as Exceptions;
use Respect\Validation\Validator as v;
use Railken\Laravel\Manager\ModelValidator;


class UserValidator extends ModelValidator
{

    /**
     * Validate username
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateUsername(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        !$entity->exists && !$parameters->exists('username') &&
            $errors->push(new Exceptions\UserUsernameNotDefinedException($parameters->get('username')));

        $parameters->exists('username') &&
            !(v::length(3, 32)->validate($parameters->get('username'))) &&
            $errors->push(new Exceptions\UserUsernameNotValidException($parameters->get('username')));


        return $errors;
    }

    /**
     * Validate email
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateEmail(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        !$entity->exists && !$parameters->exists('email') &&
            $errors->push(new Exceptions\UserEmailNotDefinedException($parameters->get('email')));

        $parameters->exists('email') &&
            !filter_var($parameters->get('email'), FILTER_VALIDATE_EMAIL) &&
            $errors->push(new Exceptions\UserEmailNotValidException($parameters->get('email')));


        return $errors;
    }

    /**
     * Validate role
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validateRole(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();
        
        $parameters->exists('role') &&
            !in_array($parameters->get('role'), [User::ROLE_USER, User::ROLE_ADMIN]) &&
            $errors->push(new Exceptions\UserRoleNotValidException($parameters->get('role')));


        return $errors;
    }

    /**
     * Validate password
     *
     * @param EntityContract $entity
     * @param ParameterBag $parameters
     *
     * @return Collection
     */
    public function validatePassword(EntityContract $entity, ParameterBag $parameters)
    {
        $errors = new Collection();

        !$entity->exists && !$parameters->exists('password') &&
            $errors->push(new Exceptions\UserPasswordNotDefinedException($parameters->get('password')));

        $parameters->exists('password') &&
            !(v::length(8,128)->validate($parameters->get('password'))) &&
            $errors->push(new Exceptions\UserPasswordNotValidException($parameters->get('password')));


        return $errors;
    }

}