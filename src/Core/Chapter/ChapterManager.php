<?php

namespace Core\Chapter;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\ParameterBag;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Tokens;

class ChapterManager extends ModelManager
{

    /**
     * List of all exceptions
     *
     * @var array
     */
    protected $exceptions = [
        Tokens::NOT_AUTHORIZED => Exceptions\ChapterNotAuthorizedException::class
    ];

    
    /**
     * Construct
     */
    public function __construct(AgentContract $agent = null)
    {
        parent::__construct($agent);
    }
}
