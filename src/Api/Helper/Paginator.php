<?php

namespace Api\Helper;

class Paginator
{

    /**
     * Retrieve
     *
     * @return New instance
     */
    public static function retrieve($query, $page, $take)
    {
        return (new self)
            ->setQuery($query)
            ->setPage($page)
            ->setTake($take)
            ->execute();
    }

    /**
     * Setter
     *
     * @param integer $total
     *
     * @return $this
     */
    public function setQuery($query)
    {
        $this->query = clone $query;
        return $this;
    }

    /**
     * Getter
     *
     * @return integer
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Setter
     *
     * @param integer $total
     *
     * @return $this
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Getter
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Setter
     *
     * @param integer $take
     *
     * @return $this
     */
    public function setTake($take)
    {
        $this->take = $take;
        $this->show = $take;

        return $this;
    }

    /**
     * Getter
     *
     * @return integer
     */
    public function getTake()
    {
        return $this->take;
    }

    /**
     * Setter
     *
     * @param integer $page
     *
     * @return $this
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Set count
     *
     * @param integer $count
     *
     * @return $this
     */
    public function setCount($count)
    {
        $this->count = $count;
        
        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Getter
     *
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Execute
     *
     * @return this
     */
    public function execute()
    {
        $total = $this->query->count();
        $this->setTotal($total);

        $page = $this->getPage();
        $first = ($page - 1) * $this->getTake();
        $last = $first + $this->getTake();

        if ($last > $total) {
            $last = $total;
        }

        $this->setMaxResults($this->getTake());
        $this->setFirstResult($first);
        $this->setFrom($first);
        $this->setTo($last);

        $this->setPages(ceil($total / $this->getTake()));

        return $this;
    }

    /**
     * Set max results
     *
     * @param int $max_results
     *
     * @return $this
     */
    public function setMaxResults($max_results)
    {
        $this->max_results = $max_results;

        return $this;
    }

    /**
     * Set first result
     *
     * @param integer $first_result
     *
     * @return $this
     */
    public function setFirstResult($first_result)
    {
        $this->first_result = $first_result;

        return $this;
    }

    /**
     * Get max results
     *
     * @return integer
     */
    public function getMaxResults()
    {
        return $this->max_results;
    }

    /**
     * Get first result
     *
     * @return integer
     */
    public function getFirstResult()
    {
        return $this->first_result;
    }

    /**
     * Set total pages
     *
     * @param integer $pages
     *
     * @return $this
     */
    public function setPages($pages)
    {
        $this->pages = $pages;

        return $this;
    }

    /**
     * Set from
     *
     * @param $from
     *
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Set to
     *
     * @param intger $to
     *
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }
}
