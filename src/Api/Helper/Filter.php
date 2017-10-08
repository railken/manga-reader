<?php

namespace Api\Helper;

class Filter
{

    protected $only = [];

    public function __construct($only = [])
    {
        $this->only = $only;
    }

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

        if (is_string($filter)) {
            $filter = $this->stringToObj($filter);
        }

        if (!$filter)
            return;


        if (is_array($filter)) {
            foreach($filter as $s_filter)
                $this->build($query, $s_filter, $logic_operator);

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


        if (!in_array($key, $this->only))
            return;

        $operator == "in"           && $query->{"{$sub_where}In"}($key, $values);

        $operator == "start_with"   && $query->{"{$sub_where}"}($key, 'like', '%'.$values);
        $operator == "end_with"     && $query->{"{$sub_where}"}($key, 'like', $values.'%');
        $operator == "contains"     && $query->{"{$sub_where}"}($key, 'like', '%'.$values.'%');

        $operator == "eq"           && $query->{"{$sub_where}"}($key, '=', $values);
        $operator == "gt"           && $query->{"{$sub_where}"}($key, '>', $values);
        $operator == "lt"           && $query->{"{$sub_where}"}($key, '<', $values);
    }

    public function stringToObj($string)
    {

        try {
            $filter = "(".$string.")";

            $buffer_string = "";
            $node = new \stdClass();
            $node->parts = [];
            $node->childs = [];
            $in_string = false;
            $escape = false;
            foreach (str_split($filter) as $char) {

                if ($char == "\\") {
                    $escape = !$escape;
                }

                if ($char == "\"" && !$escape) {
                    $in_string = !$in_string;
                }

                if ($in_string) {

                    $buffer_string .= $char;
                } else {

                    switch ($char) {

                        case  "(": 
                            if (!empty(trim($buffer_string)))
                                $node->parts[] = $buffer_string;

                            $new = new \stdClass();
                            $new->parts = [];
                            $new->childs = [];
                            $new->parent = $node;
                            $new->parent->childs[] = $new;
                            $node = $new;
                            $buffer_string = "";

                        break;

                        case ")":

                            if (!empty(trim($buffer_string)))
                                $node->parts[] = $buffer_string;


                            # // Going up? Resolving current parts.

                            $current_operator = null;
                            $current_key = null;
                            $current_value = null;
                            $last_logic_operator = "and";


                            // 
                            $subs = [];

                            foreach ($node->parts as $part) {

                                switch ($part) {
                                    case "or": case "and":
                                        $current_key = null;
                                        $current_value = null;
                                        $current_operator = null;
                                        $last_logic_operator = $part;
                                    break;

                                    case "eq": case "gt": case "lt": case "in": case "contains":

                                        if ($current_key !== null)
                                            $current_operator = $part;

                                    break;

                                    default:

                                        if ($current_key !== null && $current_value == null) {
                                            $current_value = $part;
                                        }

                                        if ($current_key == null) {
                                            $current_key = $part;
                                        } 


                                    break;
                                }

                                if ($current_key !== null && $current_operator !== null && $current_value !== null) {

                                    if ($current_value[0] == "\"")
                                        $current_value = substr($current_value, 1, -1);

                                    $sub = new \stdClass();
                                    $sub->o = $current_operator;
                                    $sub->k = $current_key;
                                    $sub->v = $current_value;
                                    $subs[] = $sub;
                                }
                            }

                            $node->v = array_merge($node->childs, $subs);
                            $node->o = $last_logic_operator;
                            unset($node->parts);
                            unset($node->childs);

                            $child = $node;
                            $node = $node->parent;
                            unset($child->parent);
                            $buffer_string = "";

                        break;

                        case " ":
                            if (!empty(trim($buffer_string)))
                                $node->parts[] = $buffer_string;

                            $buffer_string = "";
                        break;

                        default:

                            $buffer_string .= $char;
                        break;

                    }

                }

                if ($char != "\\") {
                    $escape = false;
                }
            }


            if ($in_string) {
                # error...
            }

            return $node->childs[0];
        
        } catch (\Exception $e) {
            throw new \Exception("Syntax error in: {$string}");
        }
    }
}
