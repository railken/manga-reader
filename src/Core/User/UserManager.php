<?php

namespace Core\User;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\ParameterBag;

class UserManager extends ModelManager
{
	/**
	 * Attributes
	 *
	 * @var array
	 */
	protected $attributes = [
		Attributes\Username\UsernameAttribute::class,
		Attributes\Email\EmailAttribute::class,
		Attributes\Password\PasswordAttribute::class,
	];

	/**
	 * Construct
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Create a confirmation email token for given user
	 *
	 * @param User $user
	 * @param string $email
	 *
	 * @return UserPendingEmail
	 */
	public function createConfirmationEmailToken(User $user, string $email)
	{

		$p = UserPendingEmail::where(['email' => $email, 'user_id' => $user->id])->first();

		if ($p)
			return $p;

		do {
			$token = str_random(8)."-".str_random(8);
			$exists = UserPendingEmail::where('token', $token)->count();
		} while ($exists > 0);

		return UserPendingEmail::create(['token' => $token, 'email' => $email, 'user_id' => $user->id]);
	}

	/**
	 * Find UserPendingEmail by token
	 *
	 * @param string $token
	 *
	 * @return UserPendingEmail
	 */
	public function findUserPendingEmailByToken(string $token)
	{
		return UserPendingEmail::where(['token' => $token])->first();
	}


}
