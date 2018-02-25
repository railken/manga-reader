<?php

namespace Core\User;

class UserService
{
    /**
     * Manager
     *
     * @var UserManager
     */
    public $manager;

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
            $this->requestConfirmEmail($user);
        }

        return $result;
    }



    /**
     * Request confirmation email
     *
     * @param User $user
     *
     * @return void
     */
    public function requestConfirmEmail(User $user)
    {
        $email = $user->pendingEmail;

        if (!$email) {
            $email = $this->manager->createConfirmationEmailToken($user, $user->email);
        }

        // Prevent spam
        if (!$email->notified_at || $email->notified_at < (new \Datetime())->modify('-10 minutes')) {
            $email->notified_at = new \DateTime();
            $email->save();
            event(new Events\UserRequestConfirmEmail($user));
        }
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
            $user->email = $result->email;
            $user->save();
            $result->delete();

            return $user;
        }

        return null;
    }

    /**
     * Request confirmation email
     *
     * @param User $user
     *
     * @return void
     */
    public function requestChangeEmail(User $user, $email)
    {
        
        $result = $this->manager->changeEmail($user, $email);


        if (!$result->ok()){
            return $result;
        }


        $email = $result->getResource();


        // Prevent spam
        if (!$email->notified_at || ($email->notified_at < (new \Datetime())->modify('+10 minutes'))) {
            $email->notified_at = new \DateTime();
            $email->save();
            event(new Events\UserRequestConfirmEmail($user));
        }

        return $result;
    }
}
