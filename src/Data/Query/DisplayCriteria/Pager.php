<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use LogicException;

class Pager implements PagerInterface
{
    const DEFAULT_MAX_LIMIT = 300;
    const DEFAULT_LIMIT = 100;
    const MIN_PAGE = 1;

    protected int $defaultLimit = self::DEFAULT_LIMIT;
    protected int $maxLimit = self::DEFAULT_MAX_LIMIT;
    protected int $page;
    protected int $limit;
    protected ?int $total = null;
    protected bool $enabled;

    public function __construct(int $page = 0, int $limit = 0)
    {
        $this->limit = $limit;

        $this->setDefaultLimit(self::DEFAULT_LIMIT);
        $this->setMaxLimit(self::DEFAULT_MAX_LIMIT);

        $this->setPage($page);
        $this->setLimit($limit);

        $this->enable();
    }

    public function disable(): void
    {
        $this->enabled = false;
    }

    public function enable(): void
    {
        $this->enabled = true;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    protected function setLimit(int $limit): void
    {
        if ($limit < 1) {
            $limit = $this->getDefaultLimit();
        }

        if ($limit > $this->getMaxLimit()) {
            $limit = $this->getMaxLimit();
        }

        $this->limit = $limit;
    }

    public function getOffset(): int
    {
        return ($this->getPage() - 1) * $this->getLimit();
    }

    public function getPage(): int
    {
        return $this->page;
    }

    protected function setPage(int $page): void
    {
        if ($page < self::MIN_PAGE) {
            $page = self::MIN_PAGE;
        }

        $this->page = $page;

        $this->fixPage();
    }

    /**
     * @throws LogicException
     */
    public function getTotal(): int
    {
        if (null === $this->total) {
            throw new LogicException('Total property is not initialized yet');
        }

        return $this->total;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
        $this->fixPage();
    }

    public function getFirstIndice(): int
    {
        return ($this->getPage() - 1) * $this->getLimit() + 1;
    }

    public function getLastIndice(): int
    {
        $last = $this->getFirstIndice() - 1 + $this->getLimit();

        if ($last > $this->getTotal()) {
            $last = $this->getTotal();
        }

        return $last;
    }

    public function getFirstPage(): int
    {
        return self::MIN_PAGE;
    }

    /**
     * Returns last page.
     *
     * @return int
     */
    public function getLastPage(): int
    {
        return (int) \max(self::MIN_PAGE, \ceil($this->getTotal() / $this->getLimit()));
    }

    public function isFirstPage(): bool
    {
        return $this->getPage() === $this->getFirstPage();
    }

    public function isLastPage(): bool
    {
        return $this->getPage() === $this->getLastPage();
    }

    public function isCurrentPage(int $page): bool
    {
        return $this->getPage() === $page;
    }

    public function getNextPage(): int
    {
        $nextPage = $this->getPage() + 1;
        $lastPage = $this->getLastPage();

        if ($lastPage < $nextPage) {
            $nextPage = $lastPage;
        }

        return $nextPage;
    }

    public function getPreviousPage(): int
    {
        $previousPage = $this->getPage() - 1;
        $firstPage = $this->getFirstPage();

        if ($firstPage > $previousPage) {
            $previousPage = $firstPage;
        }

        return $previousPage;
    }

    public function haveToPaginate(): bool
    {
        return $this->getTotal() > $this->getLimit();
    }

    public function getLinks(int $nb = 5): array
    {
        $links = [];
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

    public function getDefaultLimit(): int
    {
        return $this->defaultLimit;
    }

    public function setDefaultLimit(int $defaultLimit): void
    {
        $this->defaultLimit = $defaultLimit;
    }

    public function getMaxLimit(): int
    {
        return $this->maxLimit;
    }

    public function setMaxLimit(int $maxLimit): void
    {
        if ($this->getLimit() > $maxLimit) {
            $this->setLimit($maxLimit);
        }

        $this->maxLimit = $maxLimit;
    }

    /**
     * Fix current page, so it is not past the last one.
     */
    protected function fixPage(): void
    {
        if (null !== $this->total) {
            $this->page = \min($this->page, $this->getLastPage());
        }
    }
}
