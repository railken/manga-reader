<?php

namespace Core\Manga\Attributes\CreatedAt\Exceptions;
use Core\Manga\Exceptions\MangaAttributeException;

class MangaCreatedAtNotValidException extends MangaAttributeException
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
	protected $code = 'MANGA_CREATED_AT_NOT_VALID';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "The %s is not valid";

}
