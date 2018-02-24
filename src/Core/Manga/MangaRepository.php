<?php

namespace Core\Manga;

use Railken\Laravel\Manager\ModelRepository;

class MangaRepository extends ModelRepository
{

    /**
     * Class name entity
     *
     * @var string
     */
    public $entity = Manga::class;

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

    /**
     * Create a new query to select last released manga
     *
     * @return QueryBuilder
     *
     */
    public function newQueryLastReleased()
    {
        return $this->getQuery()->orderBy('last_chapter_released_at', 'DESC')->whereNotNull('last_chapter_released_at');
    }
}
