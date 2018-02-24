<?php

namespace Core\Chapter;

use Railken\Laravel\Manager\ModelRepository;

class ChapterRepository extends ModelRepository
{

    /**
     * Class name entity
     *
     * @var string
     */
    public $entity = Chapter::class;

    /**
     * Find a manga by id or slug
     *
     * @param mixed $key
     *
     * @return Manga
     */
    public function findOneByIdOrSlug($key)
    {
        return $this->getQuery()->orWhere('id', $key)->orWhere('slug', $key)->first();
    }
}
