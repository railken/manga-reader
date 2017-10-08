<?php

namespace Core\User;

use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\ParameterBag;

class UserParameterBag extends ParameterBag
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
