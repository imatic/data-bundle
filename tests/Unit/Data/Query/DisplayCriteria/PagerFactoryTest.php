<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

use PHPUnit\Framework\TestCase;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class PagerFactoryTest extends TestCase
{
    private $pagerFactory;

    protected function setUp(): void
    {
        $this->pagerFactory = new PagerFactory();
    }

    public function testCreatePagerShouldCreatePagerWithGivenParameters()
    {
        $pager = $this->pagerFactory->createPager(4, 200);

        $this->assertEquals(4, $pager->getPage());
        $this->assertEquals(200, $pager->getLimit());
    }

    public function testCreatePagerShouldCreatePagerWithDefaultLimitIfNoneWasGiven()
    {
        $this->pagerFactory->setDefaultLimit(121);
        $pager = $this->pagerFactory->createPager(5);

        $this->assertEquals(5, $pager->getPage());
        $this->assertEquals(121, $pager->getLimit());
    }
}
