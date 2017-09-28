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

}
