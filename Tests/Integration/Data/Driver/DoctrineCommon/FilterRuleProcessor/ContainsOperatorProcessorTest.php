<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Integration\Data\Driver\DoctrineCommon\FilterRuleProcessor;

use Doctrine\DBAL\Connection;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\ContainsOperatorProcessor;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\TextRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

class ContainsOperatorProcessorTest extends WebTestCase
{
    public function testProcessShouldReturnQbWhichReturnsResultContainingValueForDBAL()
    {
        $qb = $this->createUserQueryBuilder();

        $rule = new TextRule('name');
        $rule->setOperator(FilterOperatorMap::OPERATOR_CONTAINS);
        $rule->setValue('Ada');

        $processor = new ContainsOperatorProcessor();
        $processor->process($qb, $rule, 'u.name');

        $result = $qb->execute()->fetchAll();

        $this->assertCount(1, $result);
        $this->assertEquals('Adam', $result[0]['name']);
    }

    public function testProcessShouldReturnQbWhichReturnsResultNotContainingValueForDBAL()
    {
        $qb = $this->createUserQueryBuilder();

        $rule = new TextRule('name');
        $rule->setOperator(FilterOperatorMap::OPERATOR_NOT_CONTAINS);
        $rule->setValue('Ada');

        $processor = new ContainsOperatorProcessor();
        $processor->process($qb, $rule, 'u.name');

        $result = $qb->execute()->fetchAll();

        $this->assertCount(1, $result);
        $this->assertEquals('Eva', $result[0]['name']);
    }

    public function testProcessShouldReturnQbWhichReturnsResultContainingValueForDBALAndPostgresql()
    {
        if (!$this->isRunningOnPostgresql()) {
            $this->markTestSkipped('Test is enabled for postgresql only.');
        }

        $qb = $this->createUserQueryBuilder();

        $rule = new TextRule('name');
        $rule->setOperator(FilterOperatorMap::OPERATOR_CONTAINS);
        $rule->setValue('ada');

        $processor = new ContainsOperatorProcessor();
        $processor->process($qb, $rule, 'u.name');

        $result = $qb->execute()->fetchAll();

        $this->assertCount(1, $result);
        $this->assertEquals('Adam', $result[0]['name']);
    }

    public function testProcessShouldReturnQbWhichReturnsResultNotContainingValueForDBALAndPostgresql()
    {
        if (!$this->isRunningOnPostgresql()) {
            $this->markTestSkipped('Test is enabled for postgresql only.');
        }

        $qb = $this->createUserQueryBuilder();

        $rule = new TextRule('name');
        $rule->setOperator(FilterOperatorMap::OPERATOR_NOT_CONTAINS);
        $rule->setValue('ada');

        $processor = new ContainsOperatorProcessor();
        $processor->process($qb, $rule, 'u.name');

        $result = $qb->execute()->fetchAll();

        $this->assertCount(1, $result);
        $this->assertEquals('Eva', $result[0]['name']);
    }

    public function testProcessShouldReturnQbWhichReturnsResultContainingValueForORMAndPostgresql()
    {
        if (!$this->isRunningOnPostgresql()) {
            $this->markTestSkipped('Test is enabled for postgresql only.');
        }

        $qb = $this->getEntityManager()->getRepository('AppImaticDataBundle:User')->createQueryBuilder('u');

        $rule = new TextRule('name');
        $rule->setOperator(FilterOperatorMap::OPERATOR_CONTAINS);
        $rule->setValue('ada');

        $processor = new ContainsOperatorProcessor();
        $processor->process($qb, $rule, 'u.name');

        $result = $qb->getQuery()->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals('Adam', $result[0]->getName());
    }

    public function testProcessShouldReturnQbWhichReturnsResultNotContainingValueForORMAndPostgresql()
    {
        if (!$this->isRunningOnPostgresql()) {
            $this->markTestSkipped('Test is enabled for postgresql only.');
        }

        $qb = $this->getEntityManager()->getRepository('AppImaticDataBundle:User')->createQueryBuilder('u');

        $rule = new TextRule('name');
        $rule->setOperator(FilterOperatorMap::OPERATOR_NOT_CONTAINS);
        $rule->setValue('ada');

        $processor = new ContainsOperatorProcessor();
        $processor->process($qb, $rule, 'u.name');

        $result = $qb->getQuery()->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals('Eva', $result[0]->getName());
    }

    private function isRunningOnPostgresql()
    {
        return $this->getConnection()->getDatabasePlatform()->getName() === 'postgresql';
    }

    private function createUserQueryBuilder()
    {
        return $this->getConnection()->createQueryBuilder()
            ->select('u.name')
            ->from('test_user', 'u');
    }

    public function testProcessShouldReturnQbWhichReturnsNoResultsWithoutFunctionForDBAL()
    {
        $qb = $this->createUserQueryBuilder();

        $rule = new TextRule('name');
        $rule->setValue('ádám');

        $processor = new ContainsOperatorProcessor();
        $this->assertTrue($processor->supports($qb, $rule, 'u.name'));

        $processor->process($qb, $rule, 'u.name');
        $result = $qb->execute()->fetchAll();

        $this->assertCount(0, $result);
    }

    public function testProcessShouldReturnQbWhichReturnsOneResultWithFunctionForDBAL()
    {
        $qb = $this->createUserQueryBuilder();

        $rule = new TextRule('name');
        $rule->setValue('ádám');

        $processor = new ContainsOperatorProcessor();
        $processor->setFunction('unaccent_lower');
        $this->assertTrue($processor->supports($qb, $rule, 'u.name'));

        $processor->process($qb, $rule, 'u.name');
        $result = $qb->execute()->fetchAll();

        $this->assertCount(1, $result);
        $this->assertEquals('Adam', $result[0]['name']);
    }

    public function testProcessShouldReturnQbWhichReturnsNoResultsWithoutFunctionForORM()
    {
        $qb = $this->getEntityManager()->getRepository('AppImaticDataBundle:User')->createQueryBuilder('u');

        $rule = new TextRule('name');
        $rule->setValue('ádám');

        $processor = new ContainsOperatorProcessor();
        $this->assertTrue($processor->supports($qb, $rule, 'u.name'));

        $processor->process($qb, $rule, 'u.name');
        $result = $qb->getQuery()->getResult();

        $this->assertCount(0, $result);
    }

    public function testProcessShouldReturnQbWhichReturnsOneResultWithFunctionForORM()
    {
        $qb = $this->getEntityManager()->getRepository('AppImaticDataBundle:User')->createQueryBuilder('u');

        $rule = new TextRule('name');
        $rule->setValue('ádám');

        $processor = new ContainsOperatorProcessor();
        $processor->setFunction('unaccent_lower');
        $this->assertTrue($processor->supports($qb, $rule, 'u.name'));

        $processor->process($qb, $rule, 'u.name');
        $result = $qb->getQuery()->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals('Adam', $result[0]->getName());
    }

    /**
     * @return Connection
     */
    private function getConnection()
    {
        return $this->getEntityManager()->getConnection();
    }
}
