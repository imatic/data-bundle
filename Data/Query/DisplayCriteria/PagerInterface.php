<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

interface PagerInterface
{
    /**
     * @param int $limit
     */
    public function setLimit($limit);

    /**
     * @return int
     */
    public function getLimit();

    public function getOffset();

    /**
     * @param int $page
     */
    public function setPage($page);

    /**
     * @return int
     */
    public function getPage();

    /**
     * @param int $total
     */
    public function setTotal($total);

    /**
     * @return int
     */
    public function getTotal();

    /**
     * Returns first indice
     *
     * @return int
     */
    public function getFirstIndice();

    /**
     * Returns last indice
     *
     * @return int
     */
    public function getLastIndice();

    /**
     * Returns first page
     *
     * @return int
     */
    public function getFirstPage();

    /**
     * Returns last page
     *
     * @return int
     */
    public function getLastPage();

    /**
     * @return boolean
     */
    public function isFirstPage();

    /**
     * @return boolean
     */
    public function isLastPage();

    /**
     * @param $page
     * @return boolean
     */
    public function isCurrentPage($page);

    /**
     * Returns next page
     *
     * @return int
     */
    public function getNextPage();

    /**
     * Returns previous page
     *
     * @return int
     */
    public function getPreviousPage();

    /**
     * Returns true if total results more than page limit
     *
     * @return bool
     */
    public function haveToPaginate();

    /**
     * Return pager navigation links
     *
     * @param int $nb
     * @return array
     */
    public function getLinks($nb = 5);

    /**
     * @param int $defaultLimit
     */
    public function setDefaultLimit($defaultLimit);

    /**
     * @return int
     */
    public function getDefaultLimit();

    /**
     * @param int $maxLimit
     */
    public function setMaxLimit($maxLimit);

    /**
     * @return int
     */
    public function getMaxLimit();
}
