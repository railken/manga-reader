<?php

namespace Core\Chapter\Exceptions;

class ChapterNotFoundException extends ChapterException
{

    /**
     * The code to identify the error
     *
     * @var string
     */
    protected $code = 'Chapter_NOT_FOUND';

    /**
     * The message
     *
     * @var string
     */
    protected $message = "Not found";
}
