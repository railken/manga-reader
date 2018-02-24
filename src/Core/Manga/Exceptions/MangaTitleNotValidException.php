<?php

namespace Core\Manga\Exceptions;

class MangaTitleNotValidException extends MangaAttributeException
{

    /**
     * The reason (attribute) for which this exception is thrown
     *
     * @var string
     */
    protected $attribute = 'title';

    /**
     * The code to identify the error
     *
     * @var string
     */
    protected $code = 'MANGA_TITLE_NOT_VALID';

    /**
     * The message
     *
     * @var string
     */
    protected $message = "The %s is not valid";
}
