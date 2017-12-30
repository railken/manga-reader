<?php

namespace Core\User\Attributes\Username;


use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\AttributeContract;
use Railken\Laravel\Manager\Traits\AttributeValidateTrait;
use Core\User\Attributes\Username\Exceptions as Exceptions;
use Respect\Validation\Validator as v;

class UsernameAttribute implements AttributeContract
{

	use AttributeValidateTrait;

	/**
	 * Name attribute
	 *
	 * @var string
	 */
	protected $name = 'username';

    /**
     * Is the attribute required 
     *
     * @var boolean
     */
    protected $required = true;

    /**
     * Is the attribute unique 
     *
     * @var boolean
     */
    protected $unique = false;

    /**
     * List of all exceptions used in validation
     *
     * @var array
     */
    protected $exceptions = [
    	'not_defined' => Exceptions\UserUsernameNotDefinedException::class,
    	'not_valid' => Exceptions\UserUsernameNotValidException::class
    ];

    /**
     * Is a value valid ?
     *
     * @param EntityContract $entity
     * @param mixed $value
     *
     * @return boolean
     */
	public function valid(EntityContract $entity, $value)
	{
		return v::length(3, 32)->validate($value);
	}

}
