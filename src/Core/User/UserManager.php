<?php

namespace Core\User;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\ParameterBag;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\Tokens;

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
    public function delete(EntityContract $entity)
    {
        $entity->pendingEmail && $entity->pendingEmail->delete();
        $entity->library && $entity->library()->sync([]);
        $r = parent::delete($entity);
    }
}
