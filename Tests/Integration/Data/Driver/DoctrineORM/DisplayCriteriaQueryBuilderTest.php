<?php
namespace Imatic\Bundle\DataBundle\Tests\Integration\Data\Driver\DoctrineORM;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Rule;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Data\Filter\User\UserFilter;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;
use Imatic\Bundle\DataBundle\Data\Driver\DoctrineORM\DisplayCriteriaQueryBuilder;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Query\UserListQuery;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Entity\User;

class DisplayCriteriaQueryBuilderTest extends WebTestCase
{
    public function testAddFilterRuleShouldFilterResultsByFilterRuleBooleanYes()
    {
        $filter = new UserFilter();
        $activatedRule = new Rule\FilterRuleBoolean('activated');
        $activatedRule->setValue(Rule\FilterRuleBoolean::YES);
        $filter->add($activatedRule);

        $qb = (new UserListQuery)->build($this->getEntityManager());
        $this->getDisplayCriteriaQueryBuilder()->applyFilter($qb, $filter, new UserListQuery());
        /* @var $user User */
        $user = $qb->getQuery()->getSingleResult();
        $this->assertTrue($user->isActivated());
    }

    public function testAddFilterRuleShouldFilterResultsByFilterRuleBooleanNo()
    {
        $filter = new UserFilter();
        $activatedRule = new Rule\FilterRuleBoolean('activated');
        $activatedRule->setValue(Rule\FilterRuleBoolean::NO);
        $filter->add($activatedRule);

        $qb = (new UserListQuery)->build($this->getEntityManager());
        $this->getDisplayCriteriaQueryBuilder()->applyFilter($qb, $filter, new UserListQuery());
        /* @var $user User */
        $user = $qb->getQuery()->getSingleResult();
        $this->assertFalse($user->isActivated());
    }

    public function testAddFilterRuleShouldFilterResultsByFilterRuleChoiceShort()
    {
        $filter = new UserFilter();
        $activatedRule = new Rule\FilterRuleChoice('hairs', ['long', 'short']);
        $activatedRule->setValue('short');
        $filter->add($activatedRule);

        $qb = (new UserListQuery)->build($this->getEntityManager());
        $this->getDisplayCriteriaQueryBuilder()->applyFilter($qb, $filter, new UserListQuery());
        /* @var $user User */
        $user = $qb->getQuery()->getSingleResult();
        $this->assertEquals('short', $user->getHairs());
    }

    public function testAddFilterRuleShouldFilterResultsByFilterRuleChoiceLong()
    {
        $filter = new UserFilter();
        $activatedRule = new Rule\FilterRuleChoice('hairs', ['long', 'short']);
        $activatedRule->setValue('long');
        $filter->add($activatedRule);

        $qb = (new UserListQuery)->build($this->getEntityManager());
        $this->getDisplayCriteriaQueryBuilder()->applyFilter($qb, $filter, new UserListQuery());
        /* @var $user User */
        $user = $qb->getQuery()->getSingleResult();
        $this->assertEquals('long', $user->getHairs());
    }

    public function testAddFilterRuleShouldFilterResultsByFilterRuleDataRange1990()
    {
        $filter = new UserFilter();
        $activatedRule = new Rule\FilterRuleDateRange('birthDate');
        $activatedRule->setValue([
            'start' => new \DateTime('1985-03-05'),
            'end' => new \DateTime('1994-09-06'),
        ]);
        $filter->add($activatedRule);

        $qb = (new UserListQuery)->build($this->getEntityManager());
        $this->getDisplayCriteriaQueryBuilder()->applyFilter($qb, $filter, new UserListQuery());
        /* @var $user User */
        $user = $qb->getQuery()->getSingleResult();
        $this->assertEquals('1990-01-01', $user->getBirthDate()->format('Y-m-d'));
    }

    public function testAddFilterRuleShouldFilterResultsByFilterRuleDataRange1995()
    {
        $filter = new UserFilter();
        $activatedRule = new Rule\FilterRuleDateRange('birthDate');
        $activatedRule->setValue([
            'start' => new \DateTime('1995-03-04'),
            'end' => new \DateTime('1995-03-06'),
        ]);
        $filter->add($activatedRule);

        $qb = (new UserListQuery)->build($this->getEntityManager());
        $this->getDisplayCriteriaQueryBuilder()->applyFilter($qb, $filter, new UserListQuery());
        /* @var $user User */
        $user = $qb->getQuery()->getSingleResult();
        $this->assertEquals('1995-03-05', $user->getBirthDate()->format('Y-m-d'));
    }

    public function testAddFilterRuleShouldFilterResultsByFilterRuleNumber()
    {
        $filter = new UserFilter();
        $activatedRule = new Rule\FilterRuleNumber('id');
        $activatedRule->setValue(1);
        $filter->add($activatedRule);

        $qb = (new UserListQuery)->build($this->getEntityManager());
        $this->getDisplayCriteriaQueryBuilder()->applyFilter($qb, $filter, new UserListQuery());
        /* @var $user User */
        $user = $qb->getQuery()->getSingleResult();
        $this->assertEquals(1, $user->getId());
    }

    public function testAddFilterRuleShouldFilterResultsByFilterRuleRange2()
    {
        $filter = new UserFilter();
        $activatedRule = new Rule\FilterRuleDateRange('id');
        $activatedRule->setValue([
            'start' => 2,
            'end' => 10,
        ]);
        $filter->add($activatedRule);

        $qb = (new UserListQuery)->build($this->getEntityManager());
        $this->getDisplayCriteriaQueryBuilder()->applyFilter($qb, $filter, new UserListQuery());
        /* @var $user User */
        $user = $qb->getQuery()->getSingleResult();
        $this->assertEquals(2, $user->getId());
    }

    public function testAddFilterRuleShouldFilterResultsByFilterRuleRange1()
    {
        $filter = new UserFilter();
        $activatedRule = new Rule\FilterRuleDateRange('id');
        $activatedRule->setValue([
            'start' => -10,
            'end' => 1,
        ]);
        $filter->add($activatedRule);

        $qb = (new UserListQuery)->build($this->getEntityManager());
        $this->getDisplayCriteriaQueryBuilder()->applyFilter($qb, $filter, new UserListQuery());
        /* @var $user User */
        $user = $qb->getQuery()->getSingleResult();
        $this->assertEquals(1, $user->getId());
    }

    public function testAddFilterRuleShouldFilterResultsByFilterRuleText()
    {
        $filter = new UserFilter();
        $activatedRule = new Rule\FilterRuleText('name');
        $activatedRule->setValue('Adam');
        $filter->add($activatedRule);

        $qb = (new UserListQuery)->build($this->getEntityManager());
        $this->getDisplayCriteriaQueryBuilder()->applyFilter($qb, $filter, new UserListQuery());
        /* @var $user User */
        $user = $qb->getQuery()->getSingleResult();
        $this->assertEquals('Adam', $user->getName());
    }

    /**
     * @return DisplayCriteriaQueryBuilder
     */
    private function getDisplayCriteriaQueryBuilder()
    {
        return $this->container->get('imatic_data.doctrine.display_criteria_query_builder');
    }
}
