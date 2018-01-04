<?php

namespace Core\Chapter;

use Railken\Laravel\Manager\Contracts\ModelSerializerContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Illuminate\Support\Collection;
use Railken\Bag;

class ChapterSerializer
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

        if ($select->search('resources'))
        	$bag->set('resources', $entity->resources);

		return $bag;
	}
}
