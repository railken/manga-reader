<?php

namespace Core\Chapter\Attributes\Name\Exceptions;
use Core\Chapter\Exceptions\ChapterAttributeException;

class ChapterNameNotValidException extends ChapterAttributeException
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
	protected $code = 'CHAPTER_NAME_NOT_VALID';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s is not valid";

}
