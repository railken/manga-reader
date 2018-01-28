<?php

namespace Core\Log\Attributes\Message;


use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\AttributeContract;
use Railken\Laravel\Manager\Traits\AttributeValidateTrait;
use Core\Log\Attributes\Message\Exceptions as Exceptions;
use Respect\Validation\Validator as v;

class MessageAttribute implements AttributeContract
{

	use AttributeValidateTrait;

	/**
	 * Name attribute
	 *
	 * @var string
	 */
	protected $name = 'message';

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
    	'not_defined' => Exceptions\LogMessageNotDefinedException::class,
    	'not_valid' => Exceptions\LogMessageNotValidException::class
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
		return true;
	}

}
