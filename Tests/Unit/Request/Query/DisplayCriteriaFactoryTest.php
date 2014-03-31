<?php
namespace Imatic\Bundle\DataBundle\Tests\Unit\Request\Query;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Pager;
use Imatic\Bundle\DataBundle\Form\Type\Filter\TextRuleType;
use Imatic\Bundle\DataBundle\Request\Query\DisplayCriteriaFactory;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Form\Type\UserFilterType;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DisplayCriteriaFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $displayCriteriaFactory;

    private $currentRequest;

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
                        'value' => 'Lee'
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
            'filter' => 'user_filter'
        ]);

        $pager = $displayCriteria->getPager();
        $this->assertEquals(31, $pager->getPage());
        $this->assertEquals(123, $pager->getLimit());

        $sorter = $displayCriteria->getSorter();
        $this->assertCount(1, $sorter);
        $this->assertEquals('ASC', $sorter->getDirection('name'));

        $filter = $displayCriteria->getFilter();
        $this->assertCount(1, $filter);
        $this->assertEquals('name', $filter->getAt(0)->getColumn());
        $this->assertEquals('Lee', $filter->getAt(0)->getValue());
        $this->assertEquals('equal', $filter->getAt(0)->getOperator());
    }

    public function testCreateCriteriaShouldReturnValuesFromRequestRootIfComponentIdWasNotSpecified()
    {
        $this->currentRequest = new Request([
            'filter' => [
                'name' => [
                    'value' => 'Lee',
                    'operator' => 'not-equal',
                ],
            ],
            'sorter' => [
                'name' => 'ASC',
            ],
            'page' => 31,
            'limit' => 123,
        ]);

        $displayCriteria = $this->displayCriteriaFactory->createCriteria(['filter' => 'user_filter']);

        $pager = $displayCriteria->getPager();
        $this->assertEquals(31, $pager->getPage());
        $this->assertEquals(123, $pager->getLimit());

        $sorter = $displayCriteria->getSorter();
        $this->assertCount(1, $sorter);
        $this->assertEquals('ASC', $sorter->getDirection('name'));

        $filter = $displayCriteria->getFilter();
        $this->assertCount(1, $filter);
        $this->assertEquals('name', $filter->getAt(0)->getColumn());
        $this->assertEquals('Lee', $filter->getAt(0)->getValue());
        $this->assertEquals('not-equal', $filter->getAt(0)->getOperator());
    }

    protected function setUp()
    {
        $requestStack = $this->getMock('Symfony\Component\HttpFoundation\RequestStack');

        $requestStack
            ->expects($this->any())
            ->method('getCurrentRequest')
            ->will($this->returnCallback(function () {
                return $this->currentRequest;
            }));

        $pagerFactory = $this->getMock('Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerFactory');

        $pagerFactory
            ->expects($this->any())
            ->method('createPager')
            ->will($this->returnCallback(function ($page, $limit) {
                return new Pager($page, $limit);
            }));

        $userFilterType = new UserFilterType();
        $textFilter = new TextRuleType();
        $extensions = array(new PreloadedExtension(array(
            $userFilterType->getName() => $userFilterType,
            $textFilter->getName() => $textFilter
        ), array()));
        $formFactory = Forms::createFormFactoryBuilder()->addExtensions($extensions)->getFormFactory();

        $this->displayCriteriaFactory = new DisplayCriteriaFactory($requestStack, $pagerFactory, $formFactory);
    }
}
