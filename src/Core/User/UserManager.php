<?php

namespace Core\User;

use Railken\Laravel\Manager\ModelContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Permission\AgentContract;

use Core\User\User;

class UserManager extends ModelManager
{

	/**
	 * Construct
	 */
	public function __construct(AgentContract $agent = null)
	{
		$this->repository = new UserRepository($this);
		$this->serializer = new UserSerializer($this);

		parent::__construct($agent);
	}

	/**
	 * Fill the entity
	 *
	 * @param ModelContract $entity
	 * @param array $params
	 *
	 * @return ModelContract
	 */
	public function fill(ModelContract $entity, array $params)
	{


		$params = $this->getOnlyParams($params, ['username', 'role', 'password', 'email']);

		$entity->fill($params);

		return $entity;

	}

	/**
	 * This will prevent from saving entity with null value
	 *
	 * @param ModelContract $entity
	 *
	 * @return ModelContract
	 */
	public function save(ModelContract $entity)
	{
		$this->throwExceptionParamsNull([
			'email' => $entity->email,
			'username' => $entity->username,
		]);

		return parent::save($entity);
	}


}
