<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use LogicException;

class Pager implements PagerInterface
{
    const DEFAULT_MAX_LIMIT = 300;

    const DEFAULT_LIMIT = 100;

    const MIN_PAGE = 1;

    /**
     * @var int
     */
    protected $defaultLimit;

    /**
     * @var int
     */
    protected $maxLimit;

    /**
     * @var int Current page
     */
    protected $page;

    /**
     * @var int Items per page
     */
    protected $limit;

    /**
     * @var int Total items
     */
    protected $total;

    /**
     * @var bool
     */
    protected $enabled;

    public function __construct($page = null, $limit = null)
    {
        $this->setDefaultLimit(self::DEFAULT_LIMIT);
        $this->setMaxLimit(self::DEFAULT_MAX_LIMIT);

        $this->setPage($page);
        $this->setLimit($limit);

        $this->enable();
    }

    public function disable()
    {
        $this->enabled = false;
    }

    public function enable()
    {
        $this->enabled = true;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    protected function setLimit($limit)
    {
        $limit = intval($limit);
        if ($limit < 1) {
            $limit = $this->getDefaultLimit();
        }
        if ($limit > $this->getMaxLimit()) {
            $limit = $this->getMaxLimit();
        }
        $this->limit = $limit;
    }

    public function getOffset()
    {
        return ($this->getPage() - 1) * $this->getLimit();
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    protected function setPage($page)
    {
        $page = intval($page);
        if ($page < self::MIN_PAGE) {
            $page = self::MIN_PAGE;
        }
        $this->page = $page;
        $this->fixPage();
    }

    /**
     * @throws LogicException
     *
     * @return int
     */
    public function getTotal()
    {
        if (null === $this->total) {
            throw new LogicException('Total property is not initialized yet');
        }

        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal($total)
    {
        $this->total = intval($total);
        $this->fixPage();
    }

    /**
     * Returns first indice.
     *
     * @return int
     */
    public function getFirstIndice()
    {
        return ($this->getPage() - 1) * $this->getLimit() + 1;
    }

    /**
     * Returns last indice.
     *
     * @return int
     */
    public function getLastIndice()
    {
        $last = $this->getFirstIndice() - 1 + $this->getLimit();
        if ($last > $this->getTotal()) {
            $last = $this->getTotal();
        }

        return $last;
    }

    /**
     * Returns first page.
     *
     * @return int
     */
    public function getFirstPage()
    {
        return self::MIN_PAGE;
    }

    /**
     * Returns last page.
     *
     * @return int
     */
    public function getLastPage()
    {
        return max(self::MIN_PAGE, ceil($this->getTotal() / $this->getLimit()));
    }

    /**
     * @return bool
     */
    public function isFirstPage()
    {
        return $this->getPage() == $this->getFirstPage();
    }

    /**
     * @return bool
     */
    public function isLastPage()
    {
        return $this->getPage() == $this->getLastPage();
    }

    /**
     * @param $page
     *
     * @return bool
     */
    public function isCurrentPage($page)
    {
        return $this->getPage() == $page;
    }

    /**
     * Returns next page.
     *
     * @return int
     */
    public function getNextPage()
    {
        $nextPage = $this->getPage() + 1;
        $lastPage = $this->getLastPage();
        if ($lastPage < $nextPage) {
            $nextPage = $lastPage;
        }

        return $nextPage;
    }

    /**
     * Returns previous page.
     *
     * @return int
     */
    public function getPreviousPage()
    {
        $previousPage = $this->getPage() - 1;
        $firstPage = $this->getFirstPage();
        if ($firstPage > $previousPage) {
            $previousPage = $firstPage;
        }

        return $previousPage;
    }

    /**
     * Returns true if total results more than page limit.
     *
     * @return bool
     */
    public function haveToPaginate()
    {
        return $this->getTotal() > $this->getLimit();
    }

    /**
     * Return pager navigation links.
     *
     * @param int $nb
     *
     * @return array
     */
    public function getLinks($nb = 5)
    {
        $links = array();
        $fistPage = $this->getFirstPage();
        $lastPage = $this->getLastPage();
        $currentPage = $this->getPage();

        $firstLink = $currentPage - $nb;
        $lastLink = $currentPage + $nb;

        if ($fistPage > $firstLink) {
            $firstLink = $fistPage;
        }
        if ($lastPage < $lastLink) {
            $lastLink = $lastPage;
        }

        for ($i = $firstLink; $i <= $lastLink; ++$i) {
            $links[$i] = $i;
        }

        return $links;
    }

    /**
     * @return int
     */
    public function getDefaultLimit()
    {
        return $this->defaultLimit;
    }

    /**
     * @param int $defaultLimit
     */
    public function setDefaultLimit($defaultLimit)
    {
        $this->defaultLimit = intval($defaultLimit);
    }

    /**
     * @return int
     */
    public function getMaxLimit()
    {
        return $this->maxLimit;
    }

    /**
     * @param int $maxLimit
     */
    public function setMaxLimit($maxLimit)
    {
        $maxLimit = intval($maxLimit);
        if ($this->getLimit() > $maxLimit) {
            $this->setLimit($maxLimit);
        }
        $this->maxLimit = $maxLimit;
    }

    /**
     * Fix current page so it is not past the last one.
     */
    protected function fixPage()
    {
        if (null !== $this->total) {
            $this->page = min($this->page, $this->getLastPage());
        }
    }
}
