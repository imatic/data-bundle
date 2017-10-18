<?php
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
    public function supports($qb)
    {
        return $qb instanceof ORMQueryBuilder || $qb instanceof DBALQueryBuilder;
    }

    public function applyPager($qb, PagerInterface $pager)
    {
        $qb
            ->setFirstResult($pager->getOffset())
            ->setMaxResults($pager->getLimit());
    }

    public function applySorter($qb, SorterInterface $sorter, array $sorterMap)
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
