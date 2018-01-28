<?php

namespace Core\User;

use Railken\Laravel\Manager\Contracts\ModelSerializerContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Illuminate\Support\Collection;
use Railken\Bag;

class UserSerializer
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

        $bag->set('avatar', \Avatar::create($entity->username)->toBase64()->getEncoded());
        $bag->set('jobs', \DB::table('jobs')->count());
		return $bag;
	}

}
