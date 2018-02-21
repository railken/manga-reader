<?php

namespace Core\Chapter;

use Illuminate\Support\ServiceProvider;

class ChapterServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        Chapter::observe(ChapterObserver::class);

        ChapterManager::repository(ChapterRepository::class);
        ChapterManager::serializer(ChapterSerializer::class);
        ChapterManager::parameters(ChapterParameterBag::class);
        ChapterManager::validator(ChapterValidator::class);
        ChapterManager::authorizer(ChapterAuthorizer::class);
    }
}
