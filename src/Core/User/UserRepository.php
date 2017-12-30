<?php

namespace Core\User;

use Railken\Laravel\Manager\ModelRepository;

class UserRepository extends ModelRepository
{

	/**
	 * Class name entity
	 *
	 * @var string
	 */
	public $entity = User::class;

}
