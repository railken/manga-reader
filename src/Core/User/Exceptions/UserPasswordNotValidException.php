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
	protected $code = 'USER_PASSWORD_NOT_VALID';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s is not valid";

}
