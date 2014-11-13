<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class RequestQueryReader implements DisplayCriteriaReader
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function clearAttribute($name, $component = null, $emptyValue = null)
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

    public function readAttribute($name, $default = null, $component = null, $persistent = true)
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

    /**
     * @param string $name
     * @param string $component
     *
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
