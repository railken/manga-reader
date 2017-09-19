<?php

namespace Core\User\Exceptions;

class UserRoleNotValidException extends UserAttributeException
{

	/**
	 * The reason (attribute) for which this exception is thrown
	 *
	 * @var string
	 */
	protected $attribute = 'role';

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'USER_ROLE_INVALID';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s must be ".User::ROLE_ADMIN." or ".User::ROLE_USER."";

}