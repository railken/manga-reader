<?php

namespace Core\User;


class UserService
{
	/**
	 * Manager
	 *
	 * @var UserManager
	 */
	protected $manager;

	/**
	 * Construct
	 */
	public function __construct()
	{
		$this->manager = new UserManager();
	}

	/**
	 * Register a new account
	 *
	 * @param array $params
	 *
	 * @return ResultAction
	 */
	public function register(array $params)
	{
        $params = new UserParameterBag($params);
 
		$result = $this->manager->create($params->only(['username', 'password', 'email']));

		if ($result->ok()) {
			
			$user = $result->getResource();
			
			$this->manager->createConfirmationEmailToken($user, $user->email);

			event(new Events\UserRegistered($user));
		}



		return $result;
	}

	/**
	 * Confirm an account
	 *
	 * @param string $token
	 *
	 * @return User|null
	 */
	public function confirmEmail(string $token)
	{

		$result = $this->manager->findUserPendingEmailByToken($token);

		if ($result) {
			$user = $result->user;
			$user->enabled = 1;
			$user->save();
			$result->delete();

			return $user;
		}

		return null;
	}
}
