<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Tests\Integration\Data\Driver\DoctrineORM\FilterRuleProcessor;

use Imatic\Bundle\DataBundle\Data\Driver\DoctrineCommon\FilterRuleProcessor\BetweenOperatorProcessor;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\DateRangeRule;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity\User;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class BetweenOperatorProcessorTest extends WebTestCase
{
    public function testProcessShouldReturnQbWhichReturnsResultsBetweenValue()
    {
        $qb = $this->getEntityManager()->getRepository(User::class)->createQueryBuilder('u');

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

    public function testProcessShouldReturnQbWhichReturnsResultsFromValueIfEndIsNotSpecified()
    {
        $qb = $this->getEntityManager()->getRepository(User::class)->createQueryBuilder('u');

        $rule = new DateRangeRule('birthDate');
        $rule->setValue([
            'start' => new \DateTime('1992-01-01'),
            'end' => null,
        ]);

        $processor = new BetweenOperatorProcessor();
        $processor->process($qb, $rule, 'u.birthDate');

        $result = $qb->getQuery()->getResult();

        $this->assertCount(1, $result);
        $this->assertEquals('1995-03-05', $result[0]->getBirthDate()->format('Y-m-d'));
    }

    public function testProcessShouldReturnQbWhichReturnsResultsToValueIfStartIsNotSpecified()
    {
        $qb = $this->getEntityManager()->getRepository(User::class)->createQueryBuilder('u');

        $rule = new DateRangeRule('birthDate');
        $rule->setValue([
            'start' => null,
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
        $qb = $this->getEntityManager()->getRepository(User::class)->createQueryBuilder('u');

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
        $qb = $this->getEntityManager()->getRepository(User::class)->createQueryBuilder('u');

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
