<?php

namespace Core\User\Attributes\Username\Exceptions;
use Core\User\Exceptions\UserAttributeException;

class UserUsernameNotAuthorizedException extends UserAttributeException
{

	/**
	 * The reason (attribute) for which this exception is thrown
	 *
	 * @var string
	 */
	protected $attribute = 'username';

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'USER_USERNAME_NOT_AUTHTORIZED';
	
	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "You're not authorized to interact with %s, missing %s permission";




}
