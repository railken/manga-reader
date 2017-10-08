<?php

namespace Core\User;

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

        UserManager::repository(UserRepository::class);
        UserManager::serializer(UserSerializer::class);
        UserManager::parameters(UserParameterBag::class);
        UserManager::validator(UserValidator::class);
    }
}
