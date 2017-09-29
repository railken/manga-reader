<?php

namespace Api\Helper;

use Railken\Bag;

class Sorter
{

    /**
     * Perform the query and retrieve the information about pagination
     *
     * @return $this
     */
    public function fill($field, $direction)
    {
        $bag = new Bag();

        $bag->set('field', strtolower($field));
        $bag->set('direction', strtolower($direction));

        return $bag;
    }

}
