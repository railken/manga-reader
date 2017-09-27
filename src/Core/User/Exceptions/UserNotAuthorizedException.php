<?php

namespace Core\User\Exceptions;

use Railken\Laravel\Manager\Exceptions\ModelNotAuthorizedExceptionContract;

class UserNotAuthorizedException extends UserException implements ModelNotAuthorizedException
{

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'USER_NOT_AUTHORIZED';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "You're not authorized";
}
