<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Test\Data\Query\DisplayCriteria;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Sorter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterRule;
use PHPUnit\Framework\TestCase;

class SorterTest extends TestCase
{
    public function testSorterRuleColumn()
    {
        $sorterRule = new SorterRule('column', 'ASC');
        $this->assertEquals('column', $sorterRule->getColumn());

        $this->assertEquals('ASC', $sorterRule->getDirection());
        $this->assertEquals('asc', $sorterRule->getDirection(true));

        $this->assertTrue($sorterRule->isDirection('ASC'));
        $this->assertFalse($sorterRule->isDirection('DESC'));

        $this->assertEquals('DESC', $sorterRule->getReverseDirection());
        $this->assertEquals('desc', $sorterRule->getReverseDirection(true));

        $sorterRule = new SorterRule('column', 'DESC');

        $this->assertEquals('DESC', $sorterRule->getDirection());
        $this->assertEquals('desc', $sorterRule->getDirection(true));

        $this->assertTrue($sorterRule->isDirection('DESC'));
        $this->assertFalse($sorterRule->isDirection('ASC'));

        $this->assertEquals('ASC', $sorterRule->getReverseDirection());
        $this->assertEquals('asc', $sorterRule->getReverseDirection(true));

        $sorterRule = new SorterRule('column', 'xxx');
        $this->assertEquals('ASC', $sorterRule->getDirection());

        $sorterRule = new SorterRule('column');
        $this->assertEquals('ASC', $sorterRule->getDirection());
    }

    public function testSorterGetters()
    {
        // Sort asc
        $sorter = new Sorter([new SorterRule('column', 'ASC')]);

        $this->assertEquals('ASC', $sorter->getDirection('column'));
        $this->assertEquals('asc', $sorter->getDirection('column', true));
        $this->assertEquals('DESC', $sorter->getReverseDirection('column'));
        $this->assertEquals('desc', $sorter->getReverseDirection('column', true));
        $this->assertTrue($sorter->isSorted('column'));

        // Sort desc
        $sorter = new Sorter([new SorterRule('column', 'DESC')]);

        $this->assertEquals('DESC', $sorter->getDirection('column'));
        $this->assertTrue($sorter->isSorted('column'));

        // Invalid sort direction
        $sorter = new Sorter([new SorterRule('column', 'xxx')]);

        $this->assertEquals('ASC', $sorter->getDirection('column'));
        $this->assertTrue($sorter->isSorted('column'));

        // Undefined column
        $sorter = new Sorter([new SorterRule('column', 'asc')]);

        $this->assertEquals('ASC', $sorter->getDirection('undefined'));
        $this->assertEquals('ASC', $sorter->getReverseDirection('undefined')); //!!!
        $this->assertFalse($sorter->isSorted('undefined'));
    }
}
