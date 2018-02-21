<?php

namespace Core\Chapter;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ParameterBag;
use Core\Chapter\Exceptions as Exceptions;
use Railken\Laravel\Manager\ModelAuthorizer;
use Railken\Laravel\Manager\Tokens;


class ChapterAuthorizer extends ModelAuthorizer
{

	/**
	 * List of all permissions
	 *
	 * @var array
	 */
	protected $permissions = [
		Tokens::PERMISSION_CREATE => 'chapter.create',
		Tokens::PERMISSION_UPDATE => 'chapter.update',
		Tokens::PERMISSION_SHOW => 'chapter.show',
		Tokens::PERMISSION_REMOVE => 'chapter.remove',
	];
}
