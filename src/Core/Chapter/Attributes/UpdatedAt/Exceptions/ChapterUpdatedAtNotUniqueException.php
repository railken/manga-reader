<?php

namespace Core\Chapter\Attributes\UpdatedAt\Exceptions;

use Core\Chapter\Exceptions\ChapterAttributeException;

class ChapterUpdatedAtNotUniqueException extends ChapterAttributeException
{

    /**
     * The reason (attribute) for which this exception is thrown
     *
     * @var string
     */
    protected $attribute = 'updated_at';

    /**
     * The code to identify the error
     *
     * @var string
     */
    protected $code = 'CHAPTER_UPDATED_AT_NOT_UNIQUE';

    /**
     * The message
     *
     * @var string
     */
    protected $message = "The %s is not unique";
}
