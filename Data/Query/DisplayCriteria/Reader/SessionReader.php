<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class SessionReader implements DisplayCriteriaReader
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

    public function readAttribute($name, $default = null, $component = null, $persistent = false)
    {
        $request = $this->requestStack->getCurrentRequest();
        $value = $default;

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

        return $value;
    }

    /**
     * @param string      $name
     * @param string|null $component
     *
     * @return string|null
     */
    protected function getAttributeSessionKey($name, $component = null)
    {
        if (null !== $component) {
            return "imatic.data.display_criteria.{$component}.{$name}";
        }
    }

    public function attributeName($name)
    {
        return $name;
    }
}
