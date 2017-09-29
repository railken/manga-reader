<?php

namespace Core\Manga;

use Railken\Laravel\Manager\Contracts\ModelSerializerContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Bag;

class MangaSerializer implements ModelSerializerContract
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

		return $bag->all();
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
		$bag->set('title', $entity->title);
		$bag->set('aliases', $entity->aliases);
		$bag->set('overview', $entity->overview);
		$bag->set('status', $entity->status);
		$bag->set('created_at', $entity->created_at);
		$bag->set('updated_at', $entity->updated_at);

		return $bag;
	}
}
