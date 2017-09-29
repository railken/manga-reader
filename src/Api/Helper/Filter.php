<?php

namespace Api\Helper;

class Filter
{

    /**
     * Filter query with where 
     *
     * @param QueryBuilder $query
     * @param stdClass $filter
     * @param string $logic_operator
     *
     * @return void
     */
    public function build($query, $filter, $logic_operator = "and")
    {

        if (!$filter)
            return;

        if (is_array($filter)) {
            foreach($filter as $filter)
                $this->build($query, $filter, $logic_operator);

            return;
        }   

        $values = $filter->v;
        $operator = $filter->o;

        $key = isset($filter->k) ? $filter->k : null;


        $sub_where = ((object)['and' => 'where', 'or' => 'orWhere'])->$logic_operator;


        $operator == "or"           && $query->{"{$sub_where}"}(function($sub_query) use ($values) {
            $this->build($sub_query, $values, "or");
        });

        $operator == "and"          && $query->{"{$sub_where}"}(function($sub_query) use ($values) {
             $this->build($sub_query, $values, "and");
        });


        $operator == "in"           && $query->{"{$sub_where}In"}($key, $values);

        $operator == "start_with"   && $query->{"{$sub_where}"}($key, 'like', '%'.$values);
        $operator == "end_with"     && $query->{"{$sub_where}"}($key, 'like', $values.'%');
        $operator == "contains"     && $query->{"{$sub_where}"}($key, 'like', '%'.$values.'%');

        in_array($operator, [">", "<", "=="]) && $query->{"{$sub_where}"}($key, $operator, $values);
    }
}
