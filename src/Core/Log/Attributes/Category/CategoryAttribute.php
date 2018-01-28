<?php

namespace Core\Log\Attributes\Category;


use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Contracts\AttributeContract;
use Railken\Laravel\Manager\Traits\AttributeValidateTrait;
use Core\Log\Attributes\Category\Exceptions as Exceptions;
use Respect\Validation\Validator as v;

class CategoryAttribute implements AttributeContract
{

	use AttributeValidateTrait;

	/**
	 * Name attribute
	 *
	 * @var string
	 */
	protected $name = 'category';

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
    	'not_defined' => Exceptions\LogCategoryNotDefinedException::class,
    	'not_valid' => Exceptions\LogCategoryNotValidException::class
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
