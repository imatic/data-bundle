<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

interface PagerInterface
{
    public function disable(): void;

    public function enable(): void;

    public function isEnabled(): bool;

    public function getLimit(): int;

    public function getOffset(): int;

    public function getPage(): int;

    public function setTotal(int $total): void;

    public function getTotal(): int;

    /**
     * Returns first indice.
     */
    public function getFirstIndice(): int;

    /**
     * Returns last indice.
     */
    public function getLastIndice(): int;

    /**
     * Returns first page.
     */
    public function getFirstPage(): int;

    /**
     * Returns last page.
     */
    public function getLastPage(): int;

    public function isFirstPage(): bool;

    public function isLastPage(): bool;

    public function isCurrentPage(int $page): bool;

    /**
     * Returns next page.
     */
    public function getNextPage(): int;

    /**
     * Returns previous page.
     */
    public function getPreviousPage(): int;

    /**
     * Returns true if total results more than page limit.
     */
    public function haveToPaginate(): bool;

    /**
     * Return pager navigation links.
     *
     * @return array<int,int>
     */
    public function getLinks(int $nb = 5): array;

    public function setDefaultLimit(int $defaultLimit): void;

    public function getDefaultLimit(): int;

    public function setMaxLimit(int $maxLimit): void;

    public function getMaxLimit(): int;
}
