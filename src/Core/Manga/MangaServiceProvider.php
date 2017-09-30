<?php

namespace Core\Manga;

use Gate;
use Illuminate\Support\ServiceProvider;

class MangaServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        Manga::observe(MangaObserver::class);
        Gate::policy(Manga::class, MangaPolicy::class);

        MangaManager::repository(MangaRepository::class);
        MangaManager::serializer(MangaSerializer::class);
        MangaManager::parameters(MangaParameterBag::class);
        MangaManager::validator(MangaValidator::class);
        MangaManager::authorizer(MangaAuthorizer::class);
    }
}