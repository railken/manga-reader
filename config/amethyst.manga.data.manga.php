<?php

return [
    'table'      => 'amethyst_manga',
    'comment'    => 'Manga',
    'model'      => Railken\Amethyst\Models\Manga::class,
    'schema'     => App\Schemas\MangaSchema::class,
    'repository' => Railken\Amethyst\Repositories\MangaRepository::class,
    'serializer' => Railken\Amethyst\Serializers\MangaSerializer::class,
    'validator'  => Railken\Amethyst\Validators\MangaValidator::class,
    'authorizer' => Railken\Amethyst\Authorizers\MangaAuthorizer::class,
    'faker'      => App\Authorizers\MangaFaker::class,
    'manager'    => Railken\Amethyst\Authorizers\MangaManager::class,
];
