<?php

namespace Core\User\Exceptions;

class UserEmailInvalidException extends UserException
{

	public function toArray()
	{
		return ['class' => get_class($this)];
	}
}