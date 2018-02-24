<?php

namespace Core\User\Attributes\Username\Exceptions;

use Core\User\Exceptions\UserAttributeException;

class UserUsernameNotUniqueException extends UserAttributeException
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
    protected $code = 'USER_USERNAME_NOT_UNIQUE';

    /**
     * The message
     *
     * @var string
     */
    protected $message = "The %s is not unique";
}
