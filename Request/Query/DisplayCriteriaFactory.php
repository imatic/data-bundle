<?php
namespace Imatic\Bundle\DataBundle\Request\Query;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\DisplayCriteriaFactory as BaseDisplayCriteriaFactory;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\PagerFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @todo Podle typu dat (json/xml...) pridat servanty
 * ($this->servants['json']->getCriteria($displayCriteriaData))
 */
class DisplayCriteriaFactory extends BaseDisplayCriteriaFactory
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @param RequestStack         $requestStack
     * @param PagerFactory         $pagerFactory
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(RequestStack $requestStack, PagerFactory $pagerFactory, FormFactoryInterface $formFactory)
    {
        parent::__construct($pagerFactory, $formFactory);
        $this->requestStack = $requestStack;
    }

    /**
     * @param  string      $name
     * @param  mixed|null  $default
     * @param  string|null $component
     * @return mixed
     */
    protected function getAttribute($name, $default = null, $component = null)
    {
        $request = $this->requestStack->getCurrentRequest();

        $path = $name;
        if ($component) {
            $path = $component . '[' . $name . ']';
        }

        return $request->query->get($path, $default, true);
    }
}
