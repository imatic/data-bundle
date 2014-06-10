<?php

namespace Imatic\Bundle\DataBundle\Tests\Integration\Data\Driver\DoctrineDBAL;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteria;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\TextRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Pager;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Sorter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterRule;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Data\Filter\User\UserFilter;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\DBAL\UserListQuery;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\DBAL\UserListWithOrderNumbersQuery;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\DBAL\UsernameQuery;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\DBAL\UserQuery;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class QueryExecutorTest extends WebTestCase
{
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

        $this->assertNotEquals($firstResults[0]['id'], $secondResults[0]['id']);
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
        $this->assertEquals('Adam', $results[0]['name']);
        $this->assertEquals('Eva', $results[1]['name']);
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
        $this->assertEquals('Eva', $results[0]['name']);
        $this->assertEquals('Adam', $results[1]['name']);
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
        $this->assertEquals('Adam', $results[0]['name']);
        $this->assertEquals('Eva', $results[1]['name']);
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
        $this->assertEquals('Eva', $results[0]['name']);
        $this->assertEquals('Adam', $results[1]['name']);
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
        $this->assertEquals('Adam', $results[0]['name']);
        $this->assertEquals('Eva', $results[1]['name']);
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
        $this->assertEquals('Eva', $results[0]['name']);
        $this->assertEquals('Adam', $results[1]['name']);
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
        $this->assertEquals('Adam', $results[0]['name']);
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
        $this->assertEquals('Adam', $results[0]['name']);
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
        $this->assertEquals('Eva', $results[0]['name']);
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
        $this->assertEquals('Eva', $results[0]['name']);
    }

    public function testQueryExecutorShouldReturnSingleResultWhenQueryObjectImplementsSingleResultQueryObjectInterface()
    {
        $user = $this->getQueryExecutor()->execute(new UserQuery(1));

        $this->assertEquals(1, $user['id']);
    }

    public function testQueryExecutorShouldReturnSingleScalarResultWhenQueryObjectImplementsSingleScalarResultQueryObjectInterface()
    {
        $adamUsername = $this->getQueryExecutor()->execute(new UsernameQuery(1));

        $this->assertEquals('Adam', $adamUsername);
    }

    /**
     * @return QueryExecutorInterface
     */
    public function getQueryExecutor()
    {
        return $this->container->get('imatic_data.doctrine.dbal.query_executor');
    }
}