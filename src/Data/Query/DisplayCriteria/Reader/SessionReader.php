<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class SessionReader implements DisplayCriteriaReader
{
    protected RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function clearAttribute(string $name, string $component = null, $emptyValue = null): void
    {
        $session = $this->requestStack->getCurrentRequest()->getSession();

        if ($sessionKey = $this->getAttributeSessionKey($name, $component)) {
            if (null === $emptyValue) {
                $session->remove($sessionKey);
            } else {
                $session->set($sessionKey, $emptyValue);
            }
        }
    }

    public function readAttribute(string $name, $default = null, string $component = null, bool $persistent = false)
    {
        $request = $this->requestStack->getCurrentRequest();
        $value = $default;

        if ($persistent && $request && ($sessionKey = $this->getAttributeSessionKey($name, $component))) {
            $session = $request->getSession();

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

    protected function getAttributeSessionKey(string $name, string $component = null): ?string
    {
        if (null !== $component) {
            return "imatic.data.display_criteria.{$component}.{$name}";
        }

        return null;
    }

    public function attributeName(string $name): string
    {
        return $name;
    }
}
