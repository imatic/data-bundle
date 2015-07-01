<?php

namespace Imatic\Bundle\DataBundle\Tests\Integration\Data\Driver\DoctrineORM\RuleProcessor;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\RuleProcessor\DefaultRuleProcessor;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\ChoiceRule;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DefaultRuleProcessorTest extends WebTestCase
{
    public function testDefaultRuleProcessorWithMultipleChoiceRuleOnORMQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository('AppImaticDataBundle:User')->createQueryBuilder('u');

        $rule = new ChoiceRule('name', ['Adam' => 'Adam'], true);
        $rule->setValue(['Adam']);

        $processor = new DefaultRuleProcessor();
        $processor->process($qb, $rule, 'u.name');

        $result = $qb->getQuery()->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals('Adam', $result[0]->getName());
    }

    public function testDefaultRuleProcessorWithMultipleChoiceRuleOnDBALQueryBuilder()
    {
        $qb = $this->getConnection()->createQueryBuilder();
        $qb
            ->select('*')
            ->from('test_user')
        ;

        $rule = new ChoiceRule('name', ['Adam' => 'Adam'], true);
        $rule->setType(Type::SIMPLE_ARRAY);
        $rule->setValue(['Adam']);

        $processor = new DefaultRuleProcessor();
        $processor->process($qb, $rule, 'name');

        $result = $qb->execute()->fetchAll();

        $this->assertCount(1, $result);
        $this->assertEquals('Adam', $result[0]['name']);
    }

    /**
     * @return Connection
     */
    private function getConnection()
    {
        return $this->getEntityManager()->getConnection();
    }
}
