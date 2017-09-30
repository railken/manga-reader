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
		$this->filter(['title', 'overview', 'aliases', 'mangafox_url', 'mangafox_uid', 'mangafox_id', 'status']);

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
		$this->filter(['title', 'overview', 'aliases', 'mangafox_url', 'mangafox_uid', 'mangafox_id', 'status']);

		return $this;
	}

}
