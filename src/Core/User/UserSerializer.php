<?php

namespace Core\User;

use Railken\Laravel\Manager\Contracts\ModelSerializerContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Bag;

class UserSerializer implements ModelSerializerContract
{

	/**
	 * Serialize entity
	 *
	 * @param EntityContract $entity
	 *
	 * @return array
	 */
	public function serialize(EntityContract $entity)
	{
		$bag = $this->serializeBrief($entity);

		$bag->set('email', $entity->email);
		$bag->set('role', $entity->role);
		$bag->set('created_at', $entity->created_at->format('Y-m-d H:i:s'));
		$bag->set('updated_at', $entity->updated_at->format('Y-m-d H:i:s'));
		
		return $bag;
	}

	/**
	 * Serialize entity
	 *
	 * @param EntityContract $entity
	 *
	 * @return array
	 */
	public function serializeBrief(EntityContract $entity)
	{
		$bag = new Bag();

		$bag->set('id', $entity->id);
		$bag->set('username', $entity->username);
		$bag->set('email', $entity->email);

		return $bag;
	}
}
