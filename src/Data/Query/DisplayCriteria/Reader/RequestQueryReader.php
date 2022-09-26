<?php declare(strict_types=1);
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader;

use Imatic\Bundle\DataBundle\Utils\ArrayPathResolver;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class RequestQueryReader extends SessionReader
{
    public function readAttribute(string $name, $default = null, string $component = null, bool $persistent = false)
    {
        $request = $this->requestStack->getCurrentRequest();

        $path = $this->createAttributePath($name, $component);
        $queryValue = (new ArrayPathResolver($request->query->all()))->resolve($path);
        $value = parent::readAttribute($name, $queryValue, $component, $persistent);

        return null !== $value
            ? $value
            : $default;
    }

    protected function createAttributePath(string $attributeName, string $component = null): string
    {
        if ($component) {
            return $component . '[' . $attributeName . ']';
        }

        return $attributeName;
    }
}
