<?php

namespace Core\Chapter\Attributes\Id\Exceptions;

use Core\Chapter\Exceptions\ChapterAttributeException;

class ChapterIdNotDefinedException extends ChapterAttributeException
{

    /**
     * The reason (attribute) for which this exception is thrown
     *
     * @var string
     */
    protected $attribute = 'id';

    /**
     * The code to identify the error
     *
     * @var string
     */
    protected $code = 'CHAPTER_ID_NOT_DEFINED';

    /**
     * The message
     *
     * @var string
     */
    protected $message = "The %s is required";
}
