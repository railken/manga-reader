<?php

namespace Core\Manga\Exceptions;

use Railken\Laravel\Manager\Exceptions\ModelNotAuthorizedExceptionContract;

class MangaNotAuthorizedException extends MangaException implements ModelNotAuthorizedException
{

	/**
	 * The code to identify the error
	 *
	 * @var string
	 */
	protected $code = 'MANGA_NOT_AUTHORIZED';

	/**
	 * The message
	 *
	 * @var string
	 */
	protected $message = "You're not authorized";
}
