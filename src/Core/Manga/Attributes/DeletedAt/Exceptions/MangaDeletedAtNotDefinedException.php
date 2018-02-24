<?php

namespace Core\Manga\Attributes\DeletedAt\Exceptions;

use Core\Manga\Exceptions\MangaAttributeException;

class MangaDeletedAtNotDefinedException extends MangaAttributeException
{

    /**
     * The reason (attribute) for which this exception is thrown
     *
     * @var string
     */
    protected $attribute = 'deleted_at';

    /**
     * The code to identify the error
     *
     * @var string
     */
    protected $code = 'MANGA_DELETED_AT_NOT_DEFINED';

    /**
     * The message
     *
     * @var string
     */
    protected $message = "The %s is required";
}
