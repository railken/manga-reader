<?php

namespace Core\Log\Attributes\Type;


use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\AttributeContract;
use Railken\Laravel\Manager\Traits\AttributeValidateTrait;
use Core\Log\Attributes\Type\Exceptions as Exceptions;
use Respect\Validation\Validator as v;

class TypeAttribute implements AttributeContract
{

	use AttributeValidateTrait;

	/**
	 * Name attribute
	 *
	 * @var string
	 */
	protected $name = 'type';

    /**
     * Is the attribute required 
     *
     * @var boolean
     */
    protected $required = false;

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
    	'not_defined' => Exceptions\LogTypeNotDefinedException::class,
    	'not_valid' => Exceptions\LogTypeNotValidException::class
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
		return v::length(1, 255)->validate($value);
	}

}
