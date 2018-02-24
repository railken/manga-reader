<?php

namespace Core\Manga\Exceptions;

class MangaAliasesNotDefinedException extends MangaAttributeException
{

    /**
     * The reason (attribute) for which this exception is thrown
     *
     * @var string
     */
    protected $attribute = 'aliases';

    /**
     * The code to identify the error
     *
     * @var string
     */
    protected $code = 'MANGA_ALIASES_NOT_DEFINED';

    /**
     * The message
     *
     * @var string
     */
    protected $message = "The %s is required";
}
