<?php

namespace Core\Manga;

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
        MangaManager::repository(MangaRepository::class);
        MangaManager::serializer(MangaSerializer::class);
        MangaManager::parameters(MangaParameterBag::class);
        MangaManager::validator(MangaValidator::class);
    }
}
