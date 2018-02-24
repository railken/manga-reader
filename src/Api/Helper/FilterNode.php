<?php

namespace Api\Helper;

class FilterNode
{

    /**
     * Value/Values of node
     *
     * @var mixed
     */
    public $value;

    /**
     * Operator of node
     *
     * @var string
     */
    public $operator;

    /**
     * Key/Attribute
     *
     * @param string
     */
    public $key;

    /**
     * Construct
     *
     * @param string $key
     * @param string $operator
     * @param mixed $value
     */
    public function __construct($key, $operator, $value)
    {
        $this->key = $key;
        $this->operator = $operator;
        $this->value = $value;
    }
}
