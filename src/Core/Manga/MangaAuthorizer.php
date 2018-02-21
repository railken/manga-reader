<?php

namespace Core\Manga;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ParameterBag;
use Core\Manga\Exceptions as Exceptions;
use Railken\Laravel\Manager\ModelAuthorizer;
use Railken\Laravel\Manager\Tokens;


class MangaAuthorizer extends ModelAuthorizer
{

	/**
	 * List of all permissions
	 *
	 * @var array
	 */
	protected $permissions = [
		Tokens::PERMISSION_CREATE => 'manga.create',
		Tokens::PERMISSION_UPDATE => 'manga.update',
		Tokens::PERMISSION_SHOW => 'manga.show',
		Tokens::PERMISSION_REMOVE => 'manga.remove',
	];
}
