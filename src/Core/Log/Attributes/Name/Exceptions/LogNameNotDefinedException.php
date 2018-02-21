<?php

namespace Core\Log\Attributes\Name\Exceptions;
use Core\Log\Exceptions\LogAttributeException;

class LogNameNotDefinedException extends LogAttributeException
{

	/**
	 * The reason (attribute) for which this exception is thrown
	 *
	 * @var string
	 */
	protected $attribute = 'name';

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'LOG_NAME_NOT_DEFINED';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s is required";

}
