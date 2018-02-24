<?php

namespace Core\Manga\Exceptions;

class MangaMangafoxUrlNotValidException extends MangaAttributeException
{

    /**
     * The reason (attribute) for which this exception is thrown
     *
     * @var string
     */
    protected $attribute = 'mangafox_url';

    /**
     * The code to identify the error
     *
     * @var string
     */
    protected $code = 'MANGA_MANGAFOX_URL_NOT_VALID';

    /**
     * The message
     *
     * @var string
     */
    protected $message = "The %s is not valid";
}
