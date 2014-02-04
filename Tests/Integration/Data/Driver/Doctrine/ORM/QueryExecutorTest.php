<?php
namespace Imatic\Bundle\DataBundle\Tests\Data\Driver\Doctrine\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Imatic\Bundle\DataBundle\Data\Driver\Doctrine\ORM\QueryExecutor;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteria;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Pager;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Sorter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterRule;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\UserListQuery;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class QueryExecutorTest extends WebTestCase
{
    private $client;
    private $container;

    protected function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
    }

    public function testQueryExecutorShouldReturnsCorrectPagesBasedOnDisplayCriteria()
    {
        // guard
        $this->assertEquals(2, $this->getQueryExecutor()->count(new UserListQuery()));

        $firstPageCriteria = new DisplayCriteria(new Pager(1, 1), new Sorter(), new Filter());
        $firstResults = $this->getQueryExecutor()->find(new UserListQuery(), $firstPageCriteria);
        $this->assertCount(1, $firstResults);

        $secondPageCriteria = new DisplayCriteria(new Pager(2, 1), new Sorter(), new Filter());
        $secondResults = $this->getQueryExecutor()->find(new UserListQuery(), $secondPageCriteria);
        $this->assertCount(1, $secondResults);

        $this->assertNotEquals($firstResults[0]->getId(), $secondResults[0]->getId());
    }

    public function testQueryExecutorShouldReturnOnePageWithTwoResultsBasedOnDisplayCriteria()
    {
        // guard
        $this->assertEquals(2, $this->getQueryExecutor()->count(new UserListQuery()));

        $criteria = new DisplayCriteria(new Pager(1, 2), new Sorter(), new Filter());
        $results = $this->getQueryExecutor()->find(new UserListQuery(), $criteria);
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
        $results = $this->getQueryExecutor()->find(new UserListQuery(), $criteria);

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
        $results = $this->getQueryExecutor()->find(new UserListQuery(), $criteria);

        $this->assertCount(2, $results);
        $this->assertEquals('Eva', $results[0]->getName());
        $this->assertEquals('Adam', $results[1]->getName());
    }

    /**
     * @return QueryExecutor
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
}
