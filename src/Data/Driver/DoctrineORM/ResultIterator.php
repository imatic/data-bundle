<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

use Doctrine\ORM\EntityManagerInterface;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\ArrayDisplayCriteriaFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterInterface;
use Imatic\Bundle\DataBundle\Data\ResultIterator as BaseResultIterator;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ResultIterator extends BaseResultIterator
{
    private EntityManagerInterface $manager;

    public function __construct(
        QueryObjectInterface $queryObject,
        ArrayDisplayCriteriaFactory $displayCriteriaFactory,
        ?FilterInterface $filter,
        QueryExecutor $queryExecutor,
        array $criteria = []
    ) {
        $this->manager = $queryExecutor->getManager($queryObject);

        parent::__construct($queryObject, $displayCriteriaFactory, $filter, $queryExecutor, $criteria);
    }

    protected function loadNextPage(): void
    {
        $this->manager->flush();
        $this->manager->clear();

        parent::loadNextPage();
    }
}
