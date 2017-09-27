<?php

namespace Core\User;

use Gate;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        User::observe(UserObserver::class);
        Gate::policy(User::class, UserPolicy::class);

        UserManager::repository(UserRepository::class);
        UserManager::serializer(UserSerializer::class);
        UserManager::parameters(UserParameterBag::class);
        UserManager::validator(UserValidator::class);
        UserManager::authorizer(UserAuthorizer::class);
    }
}
