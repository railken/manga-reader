<?php

namespace Core\User;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\ParameterBag;

class UserManager extends ModelManager
{
	/**
	 * Attributes
	 *
	 * @var array
	 */
	protected $attributes = [
		Attributes\Username\UsernameAttribute::class,
		Attributes\Email\EmailAttribute::class,
		Attributes\Password\PasswordAttribute::class,
	];

	/**
	 * Construct
	 */
	public function __construct()
	{
		parent::__construct();
	}

}
