<?php

namespace Core\Chapter\Attributes\DeletedAt\Exceptions;
use Core\Chapter\Exceptions\ChapterAttributeException;

class ChapterDeletedAtNotAuthorizedException extends ChapterAttributeException
{

	/**
	 * The reason (attribute) for which this exception is thrown
	 *
	 * @var string
	 */
	protected $attribute = 'deleted_at';

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'CHAPTER_DELETED_AT_NOT_AUTHTORIZED';
	
	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "You're not authorized to interact with %s, missing %s permission";




}
