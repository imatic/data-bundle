<?php

namespace Imatic\Bundle\DataBundle\Tests\Integration\Data\Driver\DoctrineORM\RuleProcessor;

use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\DateRangeRule;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\RuleProcessor\BetweenOperatorProcessor;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class BetweenOperatorProcessorTest extends WebTestCase
{
    public function testProcessShouldReturnQbWhichReturnsResultsBetweenValue()
    {
        $qb = $this->getEntityManager()->getRepository('AppImaticDataBundle:User')->createQueryBuilder('u');

        $rule = new DateRangeRule('birthDate');
        $rule->setValue([
            'start' => new \DateTime('1985-01-01'),
            'end' => new \DateTime('1992-01-02'),
        ]);

        $processor = new BetweenOperatorProcessor();
        $processor->process($qb, $rule, 'u.birthDate');

        $result = $qb->getQuery()->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals('1990-01-01', $result[0]->getBirthDate()->format('Y-m-d'));
    }

    public function testProcessShouldReturnQbWhichReturnsResultsBetweenValueIncludingStart()
    {
        $qb = $this->getEntityManager()->getRepository('AppImaticDataBundle:User')->createQueryBuilder('u');

        $rule = new DateRangeRule('birthDate');
        $rule->setValue([
            'start' => new \DateTime('1990-01-01'),
            'end' => new \DateTime('1990-01-02'),
        ]);

        $processor = new BetweenOperatorProcessor();
        $processor->process($qb, $rule, 'u.birthDate');

        $result = $qb->getQuery()->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals('1990-01-01', $result[0]->getBirthDate()->format('Y-m-d'));
    }

    public function testProcessShouldReturnQbWhichReturnsResultsBetweenValueIncludingEndEvenIfTimeIsOver()
    {
        $qb = $this->getEntityManager()->getRepository('AppImaticDataBundle:User')->createQueryBuilder('u');

        $rule = new DateRangeRule('birthDate');
        $rule->setValue([
            'start' => new \DateTime('1985-01-01'),
            'end' => new \DateTime('1990-01-01'),
        ]);

        $processor = new BetweenOperatorProcessor();
        $processor->process($qb, $rule, 'u.birthDate');

        $result = $qb->getQuery()->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals('1990-01-01', $result[0]->getBirthDate()->format('Y-m-d'));
    }
}
