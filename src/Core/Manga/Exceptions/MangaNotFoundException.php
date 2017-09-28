<?php

namespace Core\Manga\Exceptions;

class $NAMENotFoundException extends MangaException
{

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'Manga_NOT_FOUND';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "Not found";
}
