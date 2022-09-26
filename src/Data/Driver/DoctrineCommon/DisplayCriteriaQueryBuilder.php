<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon;

use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaQueryBuilderInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 * @author Stepan Koci <stepan.koci@imatic.cz>
 * @author Pavel Batecko <pavel.batecko@imatic.cz>
 */
class DisplayCriteriaQueryBuilder implements DisplayCriteriaQueryBuilderInterface
{
    public function supports(object $qb): bool
    {
        return $qb instanceof ORMQueryBuilder || $qb instanceof DBALQueryBuilder;
    }

    /**
     * @param object|ORMQueryBuilder|DBALQueryBuilder $qb
     */
    public function applyPager(object $qb, PagerInterface $pager): void
    {
        $qb
            ->setFirstResult($pager->getOffset())
            ->setMaxResults($pager->getLimit());
    }

    /**
     * @param object|ORMQueryBuilder|DBALQueryBuilder $qb
     */
    public function applySorter(object $qb, SorterInterface $sorter, array $sorterMap): void
    {
        /* @var $sorterRule SorterRule */
        foreach ($sorter as $sorterRule) {
            if (!isset($sorterMap[$sorterRule->getColumn()])) {
                throw new \InvalidArgumentException(\sprintf(
                    'Column "%s" is not present in the sorter map',
                    $sorterRule->getColumn()
                ));
            }

            $qb->addOrderBy($sorterMap[$sorterRule->getColumn()], $sorterRule->getDirection());
        }
    }
}
