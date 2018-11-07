<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Data\Driver\DoctrineORM;

use Doctrine\ORM\EntityRepository;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteria;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\TextRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Pager;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Sorter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterRule;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Data\Filter\User\UserFilter;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\UserListQuery;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\UserListWithOrderNumbersQuery;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\UserListWithOrdersQuery;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\UserQuery;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class QueryExecutorTest extends WebTestCase
{
    public function testQueryExecutorShouldReturnCorrectNumberOfResultsForHasManyAssociation()
    {
        // guard
        $this->assertEquals(2, $this->getQueryExecutor()->count(new UserListWithOrdersQuery()));

        $pageCriteria = new DisplayCriteria(new Pager(1, 2), new Sorter(), new Filter());
        $result = $this->getQueryExecutor()->execute(new UserListWithOrdersQuery(), $pageCriteria);

        $this->assertCount(2, $result);
    }

    public function testQueryExecutorShouldReturnsCorrectPagesBasedOnDisplayCriteria()
    {
        // guard
        $this->assertEquals(2, $this->getQueryExecutor()->count(new UserListQuery()));

        $firstPageCriteria = new DisplayCriteria(new Pager(1, 1), new Sorter(), new Filter());
        $firstResults = $this->getQueryExecutor()->execute(new UserListQuery(), $firstPageCriteria);
        $this->assertCount(1, $firstResults);

        $secondPageCriteria = new DisplayCriteria(new Pager(2, 1), new Sorter(), new Filter());
        $secondResults = $this->getQueryExecutor()->execute(new UserListQuery(), $secondPageCriteria);
        $this->assertCount(1, $secondResults);

        $this->assertNotEquals($firstResults[0]->getId(), $secondResults[0]->getId());
    }

    public function testQueryExecutorShouldReturnOnePageWithTwoResultsBasedOnDisplayCriteria()
    {
        // guard
        $this->assertEquals(2, $this->getQueryExecutor()->count(new UserListQuery()));

        $criteria = new DisplayCriteria(new Pager(1, 2), new Sorter(), new Filter());
        $results = $this->getQueryExecutor()->execute(new UserListQuery(), $criteria);
        $this->assertCount(2, $results);
    }

    public function testQueryExecutorShouldReturnSortedResultsAscBasedOnDisplayCriteria()
    {
        // guard
        $this->assertEquals(2, $this->getQueryExecutor()->count(new UserListQuery()));

        $sorter = new Sorter([
            new SorterRule('user_name', SorterRule::ASC),
        ]);

        $criteria = new DisplayCriteria(new Pager(), $sorter, new Filter());
        $results = $this->getQueryExecutor()->execute(new UserListQuery(), $criteria);

        $this->assertCount(2, $results);
        $this->assertEquals('Adam', $results[0]->getName());
        $this->assertEquals('Eva', $results[1]->getName());
    }

    public function testQueryExecutorShouldReturnSortedResultsDescBasedOnDisplayCriteria()
    {
        // guard
        $this->assertEquals(2, $this->getQueryExecutor()->count(new UserListQuery()));

        $sorter = new Sorter([
            new SorterRule('user_name', SorterRule::DESC),
        ]);

        $criteria = new DisplayCriteria(new Pager(), $sorter, new Filter());
        $results = $this->getQueryExecutor()->execute(new UserListQuery(), $criteria);

        $this->assertCount(2, $results);
        $this->assertEquals('Eva', $results[0]->getName());
        $this->assertEquals('Adam', $results[1]->getName());
    }

    public function testQueryExecutorShouldReturnSortedResultsAscBasedOnDisplayCriteriaWithoutSpecifyingAlias()
    {
        // guard
        $this->assertEquals(2, $this->getQueryExecutor()->count(new UserListQuery()));

        $sorter = new Sorter([
            new SorterRule('name', SorterRule::ASC),
        ]);

        $criteria = new DisplayCriteria(new Pager(), $sorter, new Filter());
        $results = $this->getQueryExecutor()->execute(new UserListQuery(), $criteria);

        $this->assertCount(2, $results);
        $this->assertEquals('Adam', $results[0]->getName());
        $this->assertEquals('Eva', $results[1]->getName());
    }

    public function testQueryExecutorShouldReturnSortedResultsDescBasedOnDisplayCriteriaWithoutSpecifyingAlias()
    {
        // guard
        $this->assertEquals(2, $this->getQueryExecutor()->count(new UserListQuery()));

        $sorter = new Sorter([
            new SorterRule('name', SorterRule::DESC),
        ]);

        $criteria = new DisplayCriteria(new Pager(), $sorter, new Filter());
        $results = $this->getQueryExecutor()->execute(new UserListQuery(), $criteria);

        $this->assertCount(2, $results);
        $this->assertEquals('Eva', $results[0]->getName());
        $this->assertEquals('Adam', $results[1]->getName());
    }

    public function testQueryExecutorShouldReturnSortedResultsAscByAggregatedField()
    {
        // guard
        $this->assertEquals(2, $this->getQueryExecutor()->count(new UserListQuery()));

        $sorter = new Sorter([
            new SorterRule('order_num', SorterRule::ASC),
        ]);

        $criteria = new DisplayCriteria(new Pager(), $sorter, new Filter());
        $results = $this->getQueryExecutor()->execute(new UserListWithOrderNumbersQuery(), $criteria);

        $this->assertCount(2, $results);
        $this->assertEquals('Adam', $results[0][0]->getName());
        $this->assertEquals('Eva', $results[1][0]->getName());
    }

    public function testQueryExecutorShouldReturnSortedResultsDescByAggregatedField()
    {
        // guard
        $this->assertEquals(2, $this->getQueryExecutor()->count(new UserListQuery()));

        $sorter = new Sorter([
            new SorterRule('order_num', SorterRule::DESC),
        ]);

        $criteria = new DisplayCriteria(new Pager(), $sorter, new Filter());
        $results = $this->getQueryExecutor()->execute(new UserListWithOrderNumbersQuery(), $criteria);

        $this->assertCount(2, $results);
        $this->assertEquals('Eva', $results[0][0]->getName());
        $this->assertEquals('Adam', $results[1][0]->getName());
    }

    public function testQueryExecutorShouldReturnAdamBasedOnDisplayCriteria()
    {
        $nameRule = new TextRule('name');
        $nameRule->setValue('Adam');
        $nameRule->setOperator(FilterOperatorMap::OPERATOR_EQUAL);

        $filter = new UserFilter();
        $filter->add($nameRule);

        $criteria = new DisplayCriteria(new Pager(), new Sorter(), $filter);
        $results = $this->getQueryExecutor()->execute(new UserListQuery(), $criteria);

        $this->assertCount(1, $results);
        $this->assertEquals('Adam', $results[0]->getName());
    }

    public function testQueryExecutorShouldReturnAdamBasedOnDisplayCriteriaWithoutSpecifyingAlias()
    {
        $nameRule = new TextRule('name');
        $nameRule->setValue('Adam');
        $nameRule->setOperator(FilterOperatorMap::OPERATOR_EQUAL);

        $filter = new UserFilter();
        $filter->add($nameRule);

        $criteria = new DisplayCriteria(new Pager(), new Sorter(), $filter);
        $results = $this->getQueryExecutor()->execute(new UserListQuery(), $criteria);

        $this->assertCount(1, $results);
        $this->assertEquals('Adam', $results[0]->getName());
    }

    public function testQueryExecutorShouldReturnEvaBasedOnDisplayCriteriaWithoutSpecifyingAlias()
    {
        $nameRule = new TextRule('name');
        $nameRule->setValue('Eva');
        $nameRule->setOperator(FilterOperatorMap::OPERATOR_EQUAL);

        $filter = new UserFilter();
        $filter->add($nameRule);

        $criteria = new DisplayCriteria(new Pager(), new Sorter(), $filter);
        $results = $this->getQueryExecutor()->execute(new UserListQuery(), $criteria);

        $this->assertCount(1, $results);
        $this->assertEquals('Eva', $results[0]->getName());
    }

    public function testQueryExecutorShouldReturnEvaBasedOnDisplayCriteria()
    {
        $nameRule = new TextRule('name');
        $nameRule->setValue('Eva');
        $nameRule->setOperator(FilterOperatorMap::OPERATOR_EQUAL);

        $filter = new UserFilter();
        $filter->add($nameRule);

        $criteria = new DisplayCriteria(new Pager(), new Sorter(), $filter);
        $results = $this->getQueryExecutor()->execute(new UserListQuery(), $criteria);

        $this->assertCount(1, $results);
        $this->assertEquals('Eva', $results[0]->getName());
    }

    public function testQueryExecutorShouldReturnSingleResultWhenQueryObjectImplementsSingleResultQueryObjectInterface()
    {
        $user = $this->getQueryExecutor()->execute(new UserQuery(1));

        $this->assertEquals(1, $user->getId());
    }

    /**
     * @return QueryExecutorInterface
     */
    public function getQueryExecutor()
    {
        return self::$container->get(QueryExecutorInterface::class);
    }

    /**
     * @return EntityRepository
     */
    public function getUserRepository()
    {
        return $this->getEntityManager()->getRepository('AppImaticDataBundle:User');
    }
}
