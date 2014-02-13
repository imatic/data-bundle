<?php
namespace Imatic\Bundle\DataBundle\Tests\Data\Driver\DoctrineORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteria;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Pager;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Sorter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterRule;
use Imatic\Bundle\DataBundle\Data\Query\QueryExecutorInterface;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\UserListQuery;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\UserListWithOrderNumbersQuery;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class QueryExecutorTest extends WebTestCase
{
    private $client;

    private $container;

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
            new SorterRule('u.name', SorterRule::ASC),
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
            new SorterRule('u.name', SorterRule::DESC),
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
        $filter = new Filter([
            new FilterRule('u.name', 'Adam', '='),
        ]);

        $criteria = new DisplayCriteria(new Pager(), new Sorter(), $filter);
        $results = $this->getQueryExecutor()->execute(new UserListQuery(), $criteria);

        $this->assertCount(1, $results);
        $this->assertEquals('Adam', $results[0]->getName());
    }

    public function testQueryExecutorShouldReturnAdamBasedOnDisplayCriteriaWithoutSpecifyingAlias()
    {
        $filter = new Filter([
            new FilterRule('name', 'Adam', '='),
        ]);

        $criteria = new DisplayCriteria(new Pager(), new Sorter(), $filter);
        $results = $this->getQueryExecutor()->execute(new UserListQuery(), $criteria);

        $this->assertCount(1, $results);
        $this->assertEquals('Adam', $results[0]->getName());
    }

    public function testQueryExecutorShouldReturnEvaBasedOnDisplayCriteriaWithoutSpecifyingAlias()
    {
        $filter = new Filter([
            new FilterRule('name', 'Eva', '='),
        ]);

        $criteria = new DisplayCriteria(new Pager(), new Sorter(), $filter);
        $results = $this->getQueryExecutor()->execute(new UserListQuery(), $criteria);

        $this->assertCount(1, $results);
        $this->assertEquals('Eva', $results[0]->getName());
    }

    public function testQueryExecutorShouldReturnEvaBasedOnDisplayCriteria()
    {
        $filter = new Filter([
            new FilterRule('u.name', 'Eva', '='),
        ]);

        $criteria = new DisplayCriteria(new Pager(), new Sorter(), $filter);
        $results = $this->getQueryExecutor()->execute(new UserListQuery(), $criteria);

        $this->assertCount(1, $results);
        $this->assertEquals('Eva', $results[0]->getName());
    }

    public function testQueryExecutorShouldReturnAdamBasedOnArrayFilterInDisplayCriteria()
    {
        $filter = new Filter([
            new FilterRule('u.name', ['Adam'], 'IN'),
        ]);

        $criteria = new DisplayCriteria(new Pager(), new Sorter(), $filter);
        $results = $this->getQueryExecutor()->execute(new UserListQuery(), $criteria);

        $this->assertCount(1, $results);
        $this->assertEquals('Adam', $results[0]->getName());
    }

    public function testQueryExecutorShouldReturnEvaBasedOnArrayFilterInDisplayCriteria()
    {
        $filter = new Filter([
            new FilterRule('u.name', ['Eva'], 'IN'),
        ]);

        $criteria = new DisplayCriteria(new Pager(), new Sorter(), $filter);
        $results = $this->getQueryExecutor()->execute(new UserListQuery(), $criteria);

        $this->assertCount(1, $results);
        $this->assertEquals('Eva', $results[0]->getName());
    }

    public function testQueryExecutorShouldReturnAdamAndEvaBasedOnArrayFilterInDisplayCriteria()
    {
        $filter = new Filter([
            new FilterRule('u.name', ['Adam', 'Eva'], 'IN'),
        ]);

        $criteria = new DisplayCriteria(new Pager(), new Sorter(), $filter);
        $results = $this->getQueryExecutor()->execute(new UserListQuery(), $criteria);

        $this->assertCount(2, $results);
    }

    public function testQueryExecutorShouldReturnAdamBasedOnAggregatedFilterInDisplayCriteria()
    {
        $filter = new Filter([
            new FilterRule('order_num', 5, '<', FilterRule::CONDITION_AND, true)
        ]);

        $criteria = new DisplayCriteria(new Pager(), new Sorter(), $filter);
        $results = $this->getQueryExecutor()->execute(new UserListWithOrderNumbersQuery(), $criteria);

        $this->assertCount(1, $results);
        $this->assertEquals('Adam', $results[0][0]->getName());
    }

    public function testQueryExecutorShouldReturnEvaBasedOnAggregatedFilterInDisplayCriteria()
    {
        $filter = new Filter([
            new FilterRule('order_num', 5, '>', FilterRule::CONDITION_AND, true),
        ]);

        $criteria = new DisplayCriteria(new Pager(), new Sorter(), $filter);
        $results = $this->getQueryExecutor()->execute(new UserListWithOrderNumbersQuery(), $criteria);

        $this->assertCount(1, $results);
        $this->assertEquals('Eva', $results[0][0]->getName());
    }

    /**
     * @return QueryExecutorInterface
     */
    public function getQueryExecutor()
    {
        return $this->container->get('imatic_data.query_executor');
    }

    /**
     * @return EntityRepository
     */
    public function getUserRepository()
    {
        return $this->getEntityManager()->getRepository('AppImaticDataBundle:User');
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    protected function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
    }
}