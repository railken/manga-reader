<?php

namespace Core\Chapter;

use Railken\Laravel\Manager\Contracts\ModelSerializerContract;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelSerializer;
use Railken\Laravel\Manager\Tokens;
use Illuminate\Support\Collection;
use Core\Manga\MangaSerializer;
use Railken\Bag;

class ChapterSerializer extends Serializer
{

    /**
     * Serialize a entity field
     *
     * @param Bag $bag
     * @param Entity $entity
     * @param Collection $select
     * @param string $attribute
     * @param Serializer $serializer
     * 
     * @return void
     */
    public function serializeEntity($bag, $entity, $select, $attribute, $serializer)
    {
        
        $sub = $select->filter(function($v) use ($attribute) { 
            return preg_match("/^{$attribute}\./", $v); 
        });

        if ($sub->count() > 0) {

            $sub_select = $sub->map(function($v) use ($attribute) { 
                return preg_replace("/^{$attribute}\./", "", $v); 
            });

            $bag->$attribute = $entity->$attribute ? $serializer->serialize($entity->$attribute, $sub_select)->all() : null;
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

        if ($select->search('resources'))
            $bag->set('resources', $entity->resources);

        $this->serializeEntity($bag, $entity, $select, 'manga', new MangaSerializer());

        if ($select)
            $bag = $bag->only($select->toArray());


        $bag = $bag->only($this->manager->authorizer->getAuthorizedAttributes(Tokens::PERMISSION_SHOW, $entity)->keys()->toArray());

        return $bag;
    }

}
