<?php
namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterRule;
use Imatic\Bundle\DataBundle\Utils\ArrayPathResolver;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ExtJsReader extends SessionReader
{
    public function readAttribute($name, $default = null, $component = null, $persistent = false)
    {
        $request = $this->requestStack->getCurrentRequest();

        $path = $this->createAttributePath($name, $component);

        $value = null;
        if ($name === 'filter') {
            $value = $this->readFilter($request, $path);
        } elseif ($name === 'sorter') {
            $value = $this->readSorter($request, $component, $path);
        } else {
            $value = (new ArrayPathResolver($request->query->all()))->resolve($path);
        }

        $result = parent::readAttribute($name, $value, $component, $persistent);

        return null !== $result
            ? $result
            : $default;
    }

    protected function readFilter(Request $request, $path)
    {
        $encodedExtFilter = (new ArrayPathResolver($request->query->all()))->resolve($path, '[]');
        $decodedExtFilter = \json_decode($encodedExtFilter, true);
        if (\count($decodedExtFilter)) {
            $value = [];
            foreach ($decodedExtFilter as $filter) {
                $value[$filter['property']] = [
                    'value' => $filter['value'],
                    'operator' => FilterOperatorMap::OPERATOR_EQUAL,
                ];
            }

            return $value;
        }

        return null;
    }

    protected function readSorter(Request $request, $component, $path)
    {
        $encodedExtSorter = (new ArrayPathResolver($request->query->all()))->resolve($path, '[]');
        $decodedExtSorter = \json_decode($encodedExtSorter, true);

        $value = [];
        if (\is_array($decodedExtSorter)) {
            if (\count($decodedExtSorter)) {
                foreach ($decodedExtSorter as $sorter) {
                    $value[$sorter['property']] = $sorter['direction'];
                }
            }
        } else {
            $directionPath = $this->createAttributePath('dir', $component);
            $value[$encodedExtSorter] = $request->query->get($directionPath, SorterRule::ASC);
        }

        return \count($value) ? $value : null;
    }

    protected function createAttributePath($attributeName, $component = null)
    {
        $extAttributeName = $this->attributeName($attributeName);

        if ($component) {
            return $component . '[' . $extAttributeName . ']';
        }

        return $extAttributeName;
    }

    public function attributeName($name)
    {
        return $name === 'sorter' ? 'sort' : $name;
    }
}
