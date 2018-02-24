<?php

namespace Api\Helper;

use Railken\Bag;

class Paginator
{

    /**
     * Perform the query and retrieve the information about pagination
     *
     * @return $this
     */
    public function execute($query, $page = 1, $take = 10)
    {
        $take = (int)$take;
        $page = (int)$page;

        $take <= 0 && $take = 10;
        $page <= 0 && $page = 1;

        $total = $query->count();
        $first = ($page - 1) * $take;
        $last = $first + $take;

        if ($last > $total) {
            $last = $total;
        }

        $bag = new Bag();

        $bag->set('total', $total);
        $bag->set('max_results', $take);
        $bag->set('first_result', $first);
        $bag->set('from', $first);
        $bag->set('to', $last);
        $bag->set('pages', (ceil($total / $take)));

        return $bag;
    }
}
