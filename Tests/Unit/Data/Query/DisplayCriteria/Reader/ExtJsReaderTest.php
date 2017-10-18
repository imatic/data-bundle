<?php
namespace Imatic\Bundle\DataBundle\Tests\Unit\Data\Query\DisplayCriteria\Reader;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader\ExtJsReader;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterRule;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ExtJsReaderTest extends \PHPUnit_Framework_TestCase
{
    protected $requestStack;
    protected $currentRequest;

    protected function setUp()
    {
        $requestStack = $this->createMock('Symfony\Component\HttpFoundation\RequestStack');

        $requestStack
            ->expects($this->any())
            ->method('getCurrentRequest')
            ->will($this->returnCallback(function () {
                return $this->currentRequest;
            }));

        $this->requestStack = $requestStack;
    }

    public function testReadingAttributesFromRequest()
    {
        $this->currentRequest = new Request([
            'componentFromRequest' => [
                'filter' => \json_encode([
                    [
                        'property' => 'name',
                        'value' => 'Lee',
                    ],
                ]),
                'sort' => \json_encode([
                    [
                        'property' => 'name',
                        'direction' => 'ASC',
                    ],
                ]),
                'page' => 31,
                'limit' => 123,
            ],
        ]);

        $reader = new ExtJsReader($this->requestStack);

        $filter = $reader->readAttribute('filter', null, 'componentFromRequest');
        $this->assertEquals([
            'name' => [
                'value' => 'Lee',
                'operator' => FilterOperatorMap::OPERATOR_EQUAL,
            ],
        ], $filter);

        $sorter = $reader->readAttribute('sorter', null, 'componentFromRequest');
        $this->assertEquals([
            'name' => SorterRule::ASC,
        ], $sorter);

        $this->assertEquals(31, $reader->readAttribute('page', null, 'componentFromRequest'));
        $this->assertEquals(123, $reader->readAttribute('limit', null, 'componentFromRequest'));
    }

    public function testReadingAttributesFromRequestWithSimpleSorter()
    {
        $this->currentRequest = new Request([
            'componentFromRequest' => [
                'filter' => \json_encode([
                    [
                        'property' => 'name',
                        'value' => 'Lee',
                    ],
                ]),
                'sort' => 'name',
                'dir' => 'ASC',
                'page' => 31,
                'limit' => 123,
            ],
        ]);

        $reader = new ExtJsReader($this->requestStack);

        $filter = $reader->readAttribute('filter', null, 'componentFromRequest');
        $this->assertEquals([
            'name' => [
                'value' => 'Lee',
                'operator' => FilterOperatorMap::OPERATOR_EQUAL,
            ],
        ], $filter);

        $sorter = $reader->readAttribute('sorter', null, 'componentFromRequest');
        $this->assertEquals([
            'name' => SorterRule::ASC,
        ], $sorter);

        $this->assertEquals(31, $reader->readAttribute('page', null, 'componentFromRequest'));
        $this->assertEquals(123, $reader->readAttribute('limit', null, 'componentFromRequest'));
    }
}
