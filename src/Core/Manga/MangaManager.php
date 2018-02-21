<?php

namespace Core\Manga;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\ParameterBag;
use Railken\Laravel\Manager\Contracts\AgentContract;

class MangaManager extends ModelManager
{

    /**
     * Construct
     */
    public function __construct(AgentContract $agent = null)
    {   
    	parent::__construct($agent);
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
