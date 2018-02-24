<?php

namespace Core\Manga\Attributes\Name\Exceptions;

use Core\Manga\Exceptions\MangaAttributeException;

class MangaNameNotValidException extends MangaAttributeException
{

    /**
     * The reason (attribute) for which this exception is thrown
     *
     * @var string
     */
    protected $attribute = 'name';

    /**
     * The code to identify the error
     *
     * @var string
     */
    protected $code = 'MANGA_NAME_NOT_VALID';

    /**
     * The message
     *
     * @var string
     */
    protected $message = "The %s is not valid";
}
