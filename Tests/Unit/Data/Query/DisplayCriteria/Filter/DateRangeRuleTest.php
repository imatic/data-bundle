<?php

namespace Imatic\Bundle\DataBundle\Tests\Unit\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\DateRangeRule;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DateRangeRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testTimeShouldBeUpdatedSoStartIsAtStartOfTheDayAndEndIsAtEndOfTheDay()
    {
        $dateRangeRule = new DateRangeRule('name');
        $dateRangeRule->setValue([
            'start' => new \DateTime('2000-01-01 10:15:32'),
            'end' => new \DateTime('2000-01-05'),
        ]);

        $start = new \DateTime('2000-01-01 00:00:00');
        $end = new \DateTime('2000-01-05 23:59:59');
        $this->assertTrue($dateRangeRule->isBound());
        $this->assertEquals($start->getTimestamp(), $dateRangeRule->getValue()['start']->getTimestamp());
        $this->assertEquals($end->getTimestamp(), $dateRangeRule->getValue()['end']->getTimestamp());
    }

    public function testTimeShouldBeUpdatedSoStartIsAtStartOfTheDayAndEndIsAtEndOfTheDayIfRuleValueIsUsed()
    {
        $dateRangeRule = new DateRangeRule('name');
        $dateRangeRule->ruleValue([
            'start' => new \DateTime('2000-01-01 10:15:32'),
            'end' => new \DateTime('2000-01-05'),
        ]);

        $start = new \DateTime('2000-01-01 00:00:00');
        $end = new \DateTime('2000-01-05 23:59:59');
        $this->assertTrue($dateRangeRule->isBound());
        $this->assertEquals($start->getTimestamp(), $dateRangeRule->getValue()['start']->getTimestamp());
        $this->assertEquals($end->getTimestamp(), $dateRangeRule->getValue()['end']->getTimestamp());
    }

    public function testRuleShouldBeUnboundWhenNoValueIsSet()
    {
        $dateRangeRule = new DateRangeRule('name');
        $dateRangeRule->setValue([
            'start' => null,
            'end' => null,
        ]);

        $this->assertFalse($dateRangeRule->isBound());
    }
}
