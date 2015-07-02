<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class PagerFactory
{
    /**
     * @var int
     */
    protected $defaultLimit = 100;

    /**
     * @param int      $page
     * @param int|null $limit
     * @return PagerInterface
     */
    public function createPager($page, $limit = null)
    {
        $limit = $limit !== null ? $limit : $this->defaultLimit;

        return new Pager($page, $limit);
    }

    /**
     * @param int $defaultLimit
     */
    public function setDefaultLimit($defaultLimit)
    {
        $this->defaultLimit = $defaultLimit;
    }
}
