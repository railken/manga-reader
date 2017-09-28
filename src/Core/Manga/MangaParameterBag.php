<?php

namespace Core\Manga;

use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\Contracts\SystemAgentContract;
use Railken\Laravel\Manager\Contracts\GuestAgentContract;
use Railken\Laravel\Manager\Contracts\UserAgentContract;
use Railken\Laravel\Manager\ParameterBag;

class MangaParameterBag extends ParameterBag
{

	/**
	 * Filter current bag using agent
	 *
	 * @param AgentContract $agent
	 *
	 * @return $this
	 */
	public function filterWrite(AgentContract $agent)
	{

		$this->filter(['name']);

		if ($agent instanceof UserAgentContract) {
			// ..
		}

		if ($agent instanceof GuestAgentContract) {
		    // ..
		}

		if ($agent instanceof SystemAgentContract) {
		    // ..
		}

		return $this;
	}

	/**
	 * Filter current bag using agent for a search
	 *
	 * @param AgentContract $agent
	 *
	 * @return $this
	 */
	public function filterRead(AgentContract $agent)
	{
		$this->filter(['id', 'name', 'created_at', 'updated_at']);

		if ($agent instanceof UserAgentContract) {
			// ..
		}

		if ($agent instanceof GuestAgentContract) {
			// ..
		}

		if ($agent instanceof SystemAgentContract) {
			// ..
		}

		return $this;
	}

}
