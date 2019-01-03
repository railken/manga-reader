<?php

namespace App\Schemas;

use Railken\Amethyst\Schemas\MangaSchema as Schema;
use Railken\Lem\Attributes;

class MangaSchema extends Schema
{
    /**
     * Get all the attributes.
     *
     * @var array
     */
    public function getAttributes()
    {
        return array_merge(parent::getAttributes(), [
            Attributes\EnumAttribute::make('status', ['ongoing', 'completed']),
        ]);
    }
}
