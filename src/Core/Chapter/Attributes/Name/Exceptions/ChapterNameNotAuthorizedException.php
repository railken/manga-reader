<?php

namespace Core\Chapter\Attributes\Name\Exceptions;

use Core\Chapter\Exceptions\ChapterAttributeException;

class ChapterNameNotAuthorizedException extends ChapterAttributeException
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
    protected $code = 'CHAPTER_NAME_NOT_AUTHTORIZED';
    
    /**
     * The message
     *
     * @var string
     */
    protected $message = "You're not authorized to interact with %s, missing %s permission";
}
