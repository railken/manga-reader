<?php

namespace Core\User;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\ParameterBag;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Tokens;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

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
     * List of all exceptions
     *
     * @var array
     */
    protected $exceptions = [
        Tokens::NOT_AUTHORIZED => Exceptions\UserNotAuthorizedException::class
    ];

    /**
     * Construct
     */
    public function __construct(AgentContract $agent = null)
    {
        parent::__construct($agent);
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

        if ($p) {
            return $p;
        }

        do {
            $token = strtoupper(str_random(4)."-".str_random(4));
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

    /**
     * @inherit
     */
    public function delete(EntityContract $entity, $password = null)
    {

        if ($password !== null) {
            $errors = new Collection();

            !$this->checkPassword($entity, $password) && $errors->push(new Attributes\Password\Exceptions\UserPasswordCurrentNotValidException());

            if ($errors->count() > 0) {

                $result = new \Railken\Laravel\Manager\ResultAction();
                $result->addErrors($errors);

                return $result;
            }
        }

        $entity->pendingEmail && $entity->pendingEmail->delete();
        $entity->library && $entity->library()->sync([]);
        return parent::delete($entity);
    }

    /**
     * Change user password
     *
     * @param EntityContract $user
     * @param string $password_old,
     * @param string $password_new,
     */
    public function changePassword(EntityContract $user, $password_old, $password_new)
    {
        // Check password_old

        $errors = new Collection();

        !$this->checkPassword($user, $password_old) && $errors->push(new Attributes\Password\Exceptions\UserPasswordOldNotValidException());

        if ($errors->count() > 0) {

            $result = new \Railken\Laravel\Manager\ResultAction();
            $result->addErrors($errors);

            return $result;
        }

        return $this->update($user, new UserParameterBag(['password' => $password_new]));

    }


    /**
     * Is current password
     *
     * @param EntityContract $user
     * @param string $password
     *
     * @return boolean
     */
    public function checkPassword(EntityContract $user, $password)
    {
        return Hash::check($password, $user->password);
    }
}
