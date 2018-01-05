<?php

namespace Core\Manga;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\ParameterBag;

class MangaManager extends ModelManager
{

	/**
	 * Construct
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function follow(Manga $manga)
	{

        if (!$manga->follow) {
            $manga->follow = 1;
            $manga->save();

            dispatch((new \Sync\Jobs\SyncChaptersJob($manga))->onQueue('sync.index'));
        }
	}

}
