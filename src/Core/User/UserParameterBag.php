<?php

namespace Core\User;

use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\Contracts\SystemAgentContract;
use Railken\Laravel\Manager\Contracts\GuestAgentContract;
use Railken\Laravel\Manager\Contracts\UserAgentContract;
use Railken\Laravel\Manager\ParameterBag;

class UserParameterBag extends ParameterBag
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

		$this->filter(['username', 'password', 'email', 'role']);

		if ($agent instanceof UserAgentContract) {
			$this->filter(['username', 'password', 'email']);
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
		$this->filter(['id', 'username', 'email', 'created_at', 'updated_at']);

		return $this;
	}

}
