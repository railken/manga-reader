<?php

namespace Core\User\Attributes\Email;


use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\AttributeContract;
use Railken\Laravel\Manager\Traits\AttributeValidateTrait;
use Core\User\Attributes\Email\Exceptions as Exceptions;
use Respect\Validation\Validator as v;

class EmailAttribute implements AttributeContract
{

	use AttributeValidateTrait;

	/**
	 * Name attribute
	 *
	 * @var string
	 */
	protected $name = 'email';

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
    protected $unique = true;

    /**
     * List of all exceptions used in validation
     *
     * @var array
     */
    protected $exceptions = [
    	'not_defined' => Exceptions\UserEmailNotDefinedException::class,
    	'not_valid' => Exceptions\UserEmailNotValidException::class,
        'not_unique' => Exceptions\UserEmailNotUniqueException::class
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
		return filter_var($value, FILTER_VALIDATE_EMAIL);
	}

}
