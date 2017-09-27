<?php

namespace Core\User\Exceptions;

class UserRoleNotDefinedException extends UserAttributeException
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
	protected $code = 'USER_ROLE_NOT_DEFINED';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s is required";

}
