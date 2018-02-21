<?php

namespace Core\Chapter;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\ParameterBag;
use Railken\Laravel\Manager\Contracts\AgentContract;

class ChapterManager extends ModelManager
{

	
    /**
     * Construct
     */
    public function __construct(AgentContract $agent = null)
    {   
    	parent::__construct($agent);
    }
}
