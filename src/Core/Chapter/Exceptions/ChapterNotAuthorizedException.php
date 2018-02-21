<?php

namespace Core\Chapter\Exceptions;

class ChapterNotAuthorizedException extends ChapterException
{

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'CHAPTER_NOT_AUTHORIZED';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "You're not authorized to interact with %s, missing %s permission";

}
