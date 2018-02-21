<?php

namespace Core\User;

use Railken\Laravel\Manager\Contracts\ModelSerializerContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelSerializer;
use Railken\Laravel\Manager\Tokens;
use Illuminate\Support\Collection;
use Railken\Bag;

class UserSerializer extends ModelSerializer
{

		/**
	 * Serialize entity
	 *
	 * @param EntityContract $entity
	 * @param Collection $select
	 *
	 * @return array
	 */
	public function serialize(EntityContract $entity, Collection $select = null)
	{

        $bag = new Bag($entity->toArray());

        $bag->set('avatar', \Avatar::create($entity->username)->toBase64()->getEncoded());
        $bag->set('jobs', \DB::table('jobs')->count());

        if ($select)
        	$bag = $bag->only($select->toArray());


        $bag = $bag->only($this->manager->authorizer->getAuthorizedAttributes(Tokens::PERMISSION_SHOW, $entity)->keys()->toArray());

		return $bag;
	}


}
