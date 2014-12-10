<?php

namespace Imatic\Bundle\DataBundle\Tests\Integration\Data\Query\DisplayCriteria;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaFactory;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\ImaticDataBundle\Data\Filter\User\UserFilter;
use Imatic\Bundle\DataBundle\Tests\Fixtures\TestProject\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class DisplayCriteriaFactoryTest extends WebTestCase
{
    /**
     * @var DisplayCriteriaFactory
     */
    private $displayCriteriaFactory;

    /**
     * @var RequestStack
     */
    private $requestStack;

    protected function setUp()
    {
        parent::setUp();
        $this->displayCriteriaFactory = $this->container->get('imatic_data.display_criteria_factory');
        $this->requestStack = $this->container->get('request_stack');
    }

    public function testCreateFilterShouldCreateFilterAndPassFormInside()
    {
        $request = new Request();
        $this->requestStack->push($request);

        $filter = new UserFilter();
        $this->assertNull($filter->getForm());

        $createdFilter = $this->displayCriteriaFactory->createFilter(null, $filter);
        $this->assertInstanceOf('Symfony\Component\Form\Form', $createdFilter->getForm());
    }
}
