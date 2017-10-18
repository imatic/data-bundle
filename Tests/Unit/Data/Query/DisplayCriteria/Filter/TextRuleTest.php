<?php
namespace Imatic\Bundle\DataBundle\Tests\Unit\Data\Query\DisplayCriteria\Filter;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter\TextRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use PHPUnit_Framework_TestCase;

class TextRuleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider operatorNotRequiringValueProvider
     */
    public function testRuleShouldBeBoundWhenValueIsNotSetAndOperatorDoesntRequireIt($operator)
    {
        $textRule = new TextRule('name');

        $this->assertFalse($textRule->isBound());
        $textRule->setOperator($operator);
        $this->assertTrue($textRule->isBound());
    }

    public function operatorNotRequiringValueProvider()
    {
        return [
            [FilterOperatorMap::OPERATOR_EMPTY],
            [FilterOperatorMap::OPERATOR_NOT_EMPTY],
        ];
    }

    /**
     * @dataProvider operatorRequiringValueProvider
     */
    public function testRuleShouldBeBoundAfterValueIsSetWhenOperatorRequiresValue($operator)
    {
        $textRule = new TextRule('name');

        $this->assertFalse($textRule->isBound());
        $textRule->setOperator($operator);
        $this->assertFalse($textRule->isBound());
        $textRule->setValue('val');
        $this->assertTrue($textRule->isBound());
    }

    public function operatorRequiringValueProvider()
    {
        return [
            [FilterOperatorMap::OPERATOR_EQUAL],
            [FilterOperatorMap::OPERATOR_CONTAINS],
            [FilterOperatorMap::OPERATOR_NOT_CONTAINS],
        ];
    }
}
