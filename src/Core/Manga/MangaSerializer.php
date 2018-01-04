<?php

namespace Core\Manga;

use Railken\Laravel\Manager\Contracts\ModelSerializerContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Illuminate\Support\Collection;
use Railken\Bag;

class MangaSerializer
{

    /**
     * Serialize a collection field
     *
     * @param Bag $bag
     * @param Entity $entity
     * @param Collection $select
     * @param string $attribute
     * @param Serializer $serializer
     * @param Closure $callback
     * 
     * @return void
     */
    public function serializeCollection($bag, $entity, $select, $attribute, $serializer, $callback = null)
    {
        $sub = $select->filter(function($v) use ($attribute) { 
            return preg_match("/^{$attribute}\./", $v); 
        });

        if ($sub->count() > 0) {
            $sub_select = $sub->map(function($v) use ($attribute) { 
                return preg_replace("/^{$attribute}\./", "", $v); 
            })->values();

            $bag->$attribute = $entity->$attribute 
                ? $entity->$attribute->map(function($sub_entity) use ($serializer, $sub_select, $callback){ 
                    $bag = $serializer->serialize($sub_entity, $sub_select);

                    $callback && $callback($bag, $sub_entity);
                    
                    return $bag->all();
                })->toArray() 
                : [];


        }

    }

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

		$this->serializeCollection($bag, $entity, $select, 'chapters', new \Core\Chapter\ChapterSerializer(), function($bag, $chapter) {
            
        });


		return $bag;
	}

}
