<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM;

use Doctrine\Persistence\ObjectManager as DoctrineObjectManager;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\ArrayDisplayCriteriaFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterInterface;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Data\ResultIterator as BaseResultIterator;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ResultIterator extends BaseResultIterator
{
    /**
     * @var DoctrineObjectManager
     */
    protected $om;

    public function __construct(
        QueryObjectInterface $queryObject,
        ArrayDisplayCriteriaFactory $displayCriteriaFactory,
        FilterInterface $filter,
        QueryExecutorInterface $queryExecutor,
        DoctrineObjectManager $om,
        array $criteria = []
    ) {
        parent::__construct($queryObject, $displayCriteriaFactory, $filter, $queryExecutor, $criteria);
        $this->om = $om;
    }

    protected function loadNextPage()
    {
        $this->om->flush();
        $this->om->clear();
        parent::loadNextPage();
    }
}
