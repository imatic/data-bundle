<?php
namespace Imatic\Bundle\DataBundle\Tests\Unit\Data\Query\DisplayCriteria;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Filter as FilterRule;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Pager;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader\RequestQueryReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DisplayCriteriaFactoryTest extends TestCase
{
    private $displayCriteriaFactory;

    private $currentRequest;

    protected function setUp()
    {
        $requestStack = $this->createMock('Symfony\Component\HttpFoundation\RequestStack');

        $requestStack
            ->expects($this->any())
            ->method('getCurrentRequest')
            ->will($this->returnCallback(function () {
                return $this->currentRequest;
            }));

        $pagerFactory = $this->createMock('Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerFactory');

        $pagerFactory
            ->expects($this->any())
            ->method('createPager')
            ->will($this->returnCallback(function ($page, $limit) {
                return new Pager($page, $limit);
            }));

        $formFactory = Forms::createFormFactoryBuilder()->getFormFactory();

        $this->displayCriteriaFactory = new DisplayCriteriaFactory($pagerFactory, $formFactory, new RequestQueryReader($requestStack));
    }

    public function testcreateCriteriaShouldReturnDefaultValuesIfComponentIsNotInsideRequest()
    {
        $this->currentRequest = new Request();

        $displayCriteria = $this->displayCriteriaFactory->createCriteria();

        $this->assertEquals(1, $displayCriteria->getPager()->getPage());
        $this->assertCount(0, $displayCriteria->getFilter());
        $this->assertCount(0, $displayCriteria->getSorter());
    }

    public function testCreateCriteriaShouldReturnValuesFromRequest()
    {
        $this->currentRequest = new Request([
            'componentFromRequest' => [
                'filter' => [
                    'name' => [
                        'value' => 'Lee',
                        'operator' => FilterOperatorMap::OPERATOR_EQUAL,
                    ],
                ],
                'sorter' => [
                    'name' => 'ASC',
                ],
                'page' => 31,
                'limit' => 123,
            ],
        ]);

        $displayCriteria = $this->displayCriteriaFactory->createCriteria([
            'componentId' => 'componentFromRequest',
            'filter' => new UserFilter(),
        ]);

        $pager = $displayCriteria->getPager();
        $this->assertEquals(31, $pager->getPage());
        $this->assertEquals(123, $pager->getLimit());

        $sorter = $displayCriteria->getSorter();
        $this->assertCount(1, $sorter);
        $this->assertEquals('ASC', $sorter->getDirection('name'));

        $filter = $displayCriteria->getFilter();
        $this->assertCount(1, $filter);
        $this->assertEquals('name', $filter['name']->getName());
        $this->assertEquals('Lee', $filter['name']->getValue());
        $this->assertEquals(FilterOperatorMap::OPERATOR_EQUAL, $filter['name']->getOperator());
    }

    public function testCreateCriteriaShouldReturnValuesFromRequestRootIfComponentIdWasNotSpecified()
    {
        $this->currentRequest = new Request([
            'filter' => [
                'name' => [
                    'value' => 'Lee',
                    'operator' => FilterOperatorMap::OPERATOR_NOT_EQUAL,
                ],
            ],
            'sorter' => [
                'name' => 'ASC',
            ],
            'page' => 31,
            'limit' => 123,
        ]);

        $displayCriteria = $this->displayCriteriaFactory->createCriteria([
            'filter' => new UserFilter(),
        ]);

        $pager = $displayCriteria->getPager();
        $this->assertEquals(31, $pager->getPage());
        $this->assertEquals(123, $pager->getLimit());

        $sorter = $displayCriteria->getSorter();
        $this->assertCount(1, $sorter);
        $this->assertEquals('ASC', $sorter->getDirection('name'));

        $filter = $displayCriteria->getFilter();
        $this->assertCount(1, $filter);
        $this->assertEquals('name', $filter['name']->getName());
        $this->assertEquals('Lee', $filter['name']->getValue());
        $this->assertEquals(FilterOperatorMap::OPERATOR_NOT_EQUAL, $filter['name']->getOperator());
    }
}

class UserFilter extends Filter
{
    protected function configure()
    {
        $this
            ->add(new FilterRule\TextRule('name'));
    }
}
