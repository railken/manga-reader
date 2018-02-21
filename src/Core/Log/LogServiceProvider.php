<?php

namespace Core\Log;

use Illuminate\Support\ServiceProvider;

class LogServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        Log::observe(LogObserver::class);

        LogManager::repository(LogRepository::class);
        LogManager::serializer(LogSerializer::class);
        LogManager::parameters(LogParameterBag::class);
        LogManager::validator(LogValidator::class);
        LogManager::authorizer(LogAuthorizer::class);
    }
}
