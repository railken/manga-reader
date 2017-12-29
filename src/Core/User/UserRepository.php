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

	/**
	 * Find user by email
	 *
	 * @param string email
	 */
	public function findOneByEmail($email)
	{
		return $this->findOneBy(['email' => $email]);
	}
}
