<?php

namespace Core\Log;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\ParameterBag;

class LogManager extends ModelManager
{
	/**
	 * Attributes
	 *
	 * @var array
	 */
	protected $attributes = [
		Attributes\Message\MessageAttribute::class,
		Attributes\Category\CategoryAttribute::class,
		Attributes\Vars\VarsAttribute::class,
	];

	/**
	 * Construct
	 */
	public function __construct()
	{
		parent::__construct();
	}

}
