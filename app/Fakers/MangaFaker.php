<?php

namespace App\Fakers;

use Railken\Amethyst\Fakers\MangaFaker as Faker;

class MangaFaker extends Faker
{
    /**
     * @return \Railken\Bag
     */
    public function parameters()
    {
        $bag = parent::parameters();
        $bag->set('source', 'kissmanga');

        return $bag;
    }
}
