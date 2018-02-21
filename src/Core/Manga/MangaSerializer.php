<?php

namespace Core\Manga;

use Railken\Laravel\Manager\Contracts\ModelSerializerContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelSerializer;
use Illuminate\Support\Collection;
use Railken\Laravel\Manager\Tokens;
use Railken\Bag;

class MangaSerializer extends ModelSerializer
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
     * @param Collection $select
     *
     * @return array
     */
    public function serialize(EntityContract $entity, Collection $select = null)
    {

        $bag = new Bag($entity->toArray());

        $this->serializeCollection($bag, $entity, $select, 'chapters', new \Core\Chapter\ChapterSerializer(), function($bag, $chapter) {
            
        });

        if ($select->search('cover'))
            $bag->set('cover', $entity->cover);


        if ($select)
            $bag = $bag->only($select->toArray());


        $bag = $bag->only($this->manager->authorizer->getAuthorizedAttributes(Tokens::PERMISSION_SHOW, $entity)->keys()->toArray());

        return $bag;
    }


}
