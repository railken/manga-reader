<?php

namespace Core\User\Exceptions;

class $NAMENotFoundException extends UserException
{

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'User_NOT_FOUND';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "Not found";
}
