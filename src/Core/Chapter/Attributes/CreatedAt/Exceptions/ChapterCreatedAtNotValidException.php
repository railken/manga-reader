<?php

namespace Core\Chapter\Attributes\CreatedAt\Exceptions;

use Core\Chapter\Exceptions\ChapterAttributeException;

class ChapterCreatedAtNotValidException extends ChapterAttributeException
{

    /**
     * The reason (attribute) for which this exception is thrown
     *
     * @var string
     */
    protected $attribute = 'created_at';

    /**
     * The code to identify the error
     *
     * @var string
     */
    protected $code = 'CHAPTER_CREATED_AT_NOT_VALID';

    /**
     * The message
     *
     * @var string
     */
    protected $message = "The %s is not valid";
}
