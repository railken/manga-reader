<?php

namespace Core\Manga;

use Railken\Laravel\Manager\Contracts\ModelSerializerContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Illuminate\Support\Collection;
use Railken\Bag;

class MangaSerializer
{

	/**
	 * Serialize entity
	 *
	 * @param EntityContract $entity
	 *
	 * @return array
	 */
	public function serialize(EntityContract $entity, Collection $select)
	{
        $bag = (new Bag($entity->toArray()))->only($select->toArray());

		$bag->set('aliases', explode(";", $entity->aliases));
		$bag->set('genres', explode(";", $entity->genres));
		
		$bag->set('chapters', collect());
		return $bag;
	}

}
