<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Integration\Data\Driver\DoctrineORM\RuleProcessor;

use Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\NotBetweenOperatorProcessor;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\DateRangeRule;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class NotBetweenOperatorProcessorTest extends WebTestCase
{
    public function testProcessShouldReturnQbWhichReturnsResultsNotBetweenValue()
    {
        $qb = $this->getEntityManager()->getRepository('AppImaticDataBundle:User')->createQueryBuilder('u');

        $rule = new DateRangeRule('birthDate');
        $rule->setValue([
            'start' => new \DateTime('1985-01-01'),
            'end' => new \DateTime('1992-01-02'),
        ]);

        $processor = new NotBetweenOperatorProcessor();
        $processor->process($qb, $rule, 'u.birthDate');

        $result = $qb->getQuery()->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals('1995-03-05', $result[0]->getBirthDate()->format('Y-m-d'));
    }

    public function testProcessShouldReturnQbWhichReturnsResultsToValueIfEndIsNotSpecified()
    {
        $qb = $this->getEntityManager()->getRepository('AppImaticDataBundle:User')->createQueryBuilder('u');

        $rule = new DateRangeRule('birthDate');
        $rule->setValue([
            'start' => new \DateTime('1992-01-01'),
            'end' => null,
        ]);

        $processor = new NotBetweenOperatorProcessor();
        $processor->process($qb, $rule, 'u.birthDate');

        $result = $qb->getQuery()->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals('1990-01-01', $result[0]->getBirthDate()->format('Y-m-d'));
    }

    public function testProcessShouldReturnQbWhichReturnsResultsFromValueIfStartIsNotSpecified()
    {
        $qb = $this->getEntityManager()->getRepository('AppImaticDataBundle:User')->createQueryBuilder('u');

        $rule = new DateRangeRule('birthDate');
        $rule->setValue([
            'start' => null,
            'end' => new \DateTime('1992-01-02'),
        ]);

        $processor = new NotBetweenOperatorProcessor();
        $processor->process($qb, $rule, 'u.birthDate');

        $result = $qb->getQuery()->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals('1995-03-05', $result[0]->getBirthDate()->format('Y-m-d'));
    }

    public function testProcessShouldReturnQbWhichReturnsResultsNotBetweenValueIncludingStart()
    {
        $qb = $this->getEntityManager()->getRepository('AppImaticDataBundle:User')->createQueryBuilder('u');

        $rule = new DateRangeRule('birthDate');
        $rule->setValue([
            'start' => new \DateTime('1990-01-01'),
            'end' => new \DateTime('1990-01-02'),
        ]);

        $processor = new NotBetweenOperatorProcessor();
        $processor->process($qb, $rule, 'u.birthDate');

        $result = $qb->getQuery()->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals('1995-03-05', $result[0]->getBirthDate()->format('Y-m-d'));
    }

    public function testProcessShouldReturnQbWhichReturnsResultsNotBetweenValueIncludingEnd()
    {
        $qb = $this->getEntityManager()->getRepository('AppImaticDataBundle:User')->createQueryBuilder('u');

        $rule = new DateRangeRule('birthDate');
        $rule->setValue([
            'start' => new \DateTime('1985-01-01'),
            'end' => new \DateTime('1990-01-01'),
        ]);

        $processor = new NotBetweenOperatorProcessor();
        $processor->process($qb, $rule, 'u.birthDate');

        $result = $qb->getQuery()->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals('1995-03-05', $result[0]->getBirthDate()->format('Y-m-d'));
    }
}
