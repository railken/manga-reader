<?php

namespace Core\User\Exceptions;

class UserPasswordNotValidException extends UserAttributeException
{

	/**
	 * The reason (attribute) for which this exception is thrown
	 *
	 * @var string
	 */
	protected $attribute = 'password';

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'USER_PASSWORD_INVALID';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s must be at least 8 characters in length";
}