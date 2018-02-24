<?php

namespace Core\User;

use Railken\Laravel\Manager\ModelRepository;
use DateTime;

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


	/**
	 * Find all pending users expired
	 *
	 * @return Collection
	 */
	public function getExpiredPendingUsers()
	{
		return $this->getQuery()->where('enabled', 0)->where('created_at', '<=', (new DateTime())->modify('-3 hours'))->get();
	}
}
