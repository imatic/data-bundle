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

    public function __construct(RequestStack $requestStack, PagerFactory $pagerFactory, FormFactoryInterface $formFactory)
    {
        parent::__construct($pagerFactory, $formFactory);
        $this->requestStack = $requestStack;
    }

    protected function getAttribute($name, $default = null, $component = null, $persistent = true)
    {
        $request = $this->requestStack->getCurrentRequest();

        $path = $name;
        if ($component) {
            $path = $component . '[' . $name . ']';
        }

        $value = $request->query->get($path, null, true);

        if (
            $persistent
            && ($session = $request->getSession())
            && ($sessionKey = $this->getAttributeSessionKey($name, $component))
        ) {
            if (null === $value) {
                if ($session->has($sessionKey)) {
                    $value = $session->get($sessionKey);
                }
            } else {
                $session->set($sessionKey, $value);
            }
        }

        return null !== $value
            ? $value
            : $default
        ;
    }

    protected function clearAttribute($name, $component = null, $emptyValue = null)
    {
        if (
            ($session = ($this->requestStack->getCurrentRequest()->getSession()))
            && ($sessionKey = $this->getAttributeSessionKey($name, $component))
        ) {
            if (null === $emptyValue) {
                $session->remove($sessionKey);
            } else {
                $session->set($sessionKey, $emptyValue);
            }
        }
    }

    /**
     * Get attribute session key
     *
     * @param string $name
     * @param string $component
     * @return string|null
     */
    protected function getAttributeSessionKey($name, $component = null)
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request->attributes->has('_route') && $request->attributes->has('_route_params')) {
            return sprintf(
                'imatic.data.display_criteria.%s(%s|%s)',
                null !== $component ? "{$component}[{$name}]" : $name,
                $request->attributes->get('_route'),
                serialize($request->attributes->get('_route_params'))
            );
        }
    }
}
