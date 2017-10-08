<?php

namespace Core\User;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\ParameterBag;

class UserManager extends ModelManager
{

	/**
	 * Construct
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function register($parameters)
	{
		return $this->create($parameters);
	}
}
