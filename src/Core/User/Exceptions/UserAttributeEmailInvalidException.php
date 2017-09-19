<?php

namespace Core\User\Exceptions;

class UserAttributeEmailInvalidException extends UserAttributeException
{

	/**
	 * The reason (attribute) for which this exception is thrown
	 *
	 * @var string
	 */
	protected $attribute = 'email';

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'USER_EMAIL_INVALID';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s should be in this format foo@fee.net";
	
}