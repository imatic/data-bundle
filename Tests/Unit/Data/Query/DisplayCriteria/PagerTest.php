<?php

namespace Imatic\Bundle\DataBundle\Test\Data\Query\DisplayCriteria;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Pager;

class PagerTest extends \PHPUnit_Framework_TestCase
{
    protected $page = 3;

    protected $limit = 10;

    public function testPage()
    {
        $pager = new Pager($this->page);
        $this->assertEquals($this->page, $pager->getPage(), 'Standard set/get');

        $pager = new Pager($this->page);
        $this->assertEquals($this->page, $pager->getPage(), 'Standard set/get');

        $pager = new Pager(-10);
        $this->assertEquals($pager->getFirstPage(), $pager->getPage(), 'Setting negative page');

        $pager = new Pager(0);
        $this->assertEquals($pager->getFirstPage(), $pager->getPage(), 'Setting zero page');
    }

    public function testLimit()
    {
        $pager = new Pager(null, $this->limit);
        $this->assertEquals($this->limit, $pager->getLimit(), 'Standard set/get');

        $pager = new Pager(null, 1000);
        $pager->setMaxLimit($this->limit);
        $this->assertEquals($this->limit, $pager->getLimit(), 'Setting big limit before');

        $pager = new Pager(null, 0);
        $this->assertEquals($pager->getDefaultLimit(), $pager->getLimit(), 'Setting zero limit');

        $pager = new Pager(null, -10);
        $this->assertEquals($pager->getDefaultLimit(), $pager->getLimit(), 'Setting negative limit');
    }

    public function testIndice()
    {
        $pager = new Pager(6, 10);
        $pager->setTotal(1000);

        $this->assertEquals(51, $pager->getFirstIndice(), 'First indice');

        $this->assertEquals(60, $pager->getLastIndice(), 'Last indice');

        $pager = new Pager(null, 10);
        $pager->setTotal(1000);

        $this->assertEquals(1, $pager->getFirstIndice(), 'First indice');

        $this->assertEquals(10, $pager->getLastIndice(), 'Last indice');

        $pager = new Pager(3, 10);
        $pager->setTotal(25);

        $this->assertEquals(25, $pager->getLastIndice(), 'Last indice');
    }

    public function testOffset()
    {
        $pager = new Pager(6, 10);

        $this->assertEquals(50, $pager->getOffset());

        $pager = new Pager(null, 10);

        $this->assertEquals(0, $pager->getOffset());
    }

    public function testPaging()
    {
        $pager = new Pager(6, 10);
        $pager->setTotal(1001);

        $this->assertEquals(101, $pager->getLastPage());

        $this->assertEquals(5, $pager->getPreviousPage());

        $this->assertEquals(7, $pager->getNextPage());

        $pager = new Pager(1, 100);
        $pager->setTotal(90);

        $this->assertEquals(false, $pager->haveToPaginate());
    }

    public function testPagingLinks()
    {
        $pager = new Pager(8, 10);
        $pager->setTotal(1001);

        $links = array(
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9,
            10 => 10,
            11 => 11,
            12 => 12,
            13 => 13,
        );
        $linkNb = 5;

        $this->assertEquals($links, $pager->getLinks($linkNb));
    }
}
