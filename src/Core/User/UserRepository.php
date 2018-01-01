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
	 * Find one user by email
	 *
	 * @param string $email
	 *
	 * @return user
	 */
	public function findOneByEmail(string $email)
	{
		return $this->findOneBy(['email' => $email]);
	}
}
