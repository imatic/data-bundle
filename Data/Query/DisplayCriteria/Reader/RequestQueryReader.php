<?php
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader;

use Imatic\Bundle\DataBundle\Utils\ArrayPathResolver;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class RequestQueryReader extends SessionReader
{
    public function readAttribute($name, $default = null, $component = null, $persistent = false)
    {
        $request = $this->requestStack->getCurrentRequest();

        $path = $this->createAttributePath($name, $component);
        $queryValue = (new ArrayPathResolver($request->query->all()))->resolve($path);
        $value = parent::readAttribute($name, $queryValue, $component, $persistent);

        return null !== $value
            ? $value
            : $default;
    }

    protected function createAttributePath($attributeName, $component = null)
    {
        if ($component) {
            return $component . '[' . $attributeName . ']';
        }

        return $attributeName;
    }
}
