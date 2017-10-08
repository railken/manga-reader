<?php

namespace Core\Manga;

use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\ParameterBag;

class MangaParameterBag extends ParameterBag
{

	/**
	 * Filter current bag using agent
	 *
	 * @return $this
	 */
	public function filterWrite()
	{
		return $this;
	}

	/**
	 * Filter current bag using agent for a search
	 *
	 * @return $this
	 */
	public function filterRead()
	{

		
		return $this;
	}

}
