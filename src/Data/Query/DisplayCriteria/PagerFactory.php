<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class PagerFactory
{
    protected int $defaultLimit = 100;

    public function createPager(int $page, int $limit = null): PagerInterface
    {
        $limit = $limit !== null ? $limit : $this->defaultLimit;

        return new Pager($page, $limit);
    }

    public function setDefaultLimit(int $defaultLimit): void
    {
        $this->defaultLimit = $defaultLimit;
    }
}
