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
		$bag->set('chapters', collect());

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
		$bag->set('title', $entity->title);
		$bag->set('aliases', explode(";", $entity->aliases));
		$bag->set('overview', $entity->overview);
		$bag->set('status', $entity->status);
		$bag->set('mangafox_url', $entity->mangafox_url);
		$bag->set('mangafox_uid', $entity->mangafox_uid);
		$bag->set('mangafox_id', $entity->mangafox_id);
		$bag->set('artist', $entity->artist);
		$bag->set('author', $entity->author);
		$bag->set('genres', explode(";", $entity->genres));
		$bag->set('released_year', $entity->released_year);
		$bag->set('created_at', $entity->created_at ? $entity->created_at->format('Y-m-d') : null);
		$bag->set('updated_at', $entity->updated_at ? $entity->updated_at->format('Y-m-d') : null);

		return $bag;
	}
}
