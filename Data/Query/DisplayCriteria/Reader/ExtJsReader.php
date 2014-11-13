<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\Reader;

use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\FilterOperatorMap;
use Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria\SorterRule;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Miloslav Nenadal <miloslav.nenadal@imatic.cz>
 */
class ExtJsReader extends SessionReader
{
    public function readAttribute($name, $default = null, $component = null, $persistent = true)
    {
        $request = $this->requestStack->getCurrentRequest();

        $path = $this->createAttributePath($name, $component);

        $value = null;
        if ($name === 'filter') {
            $value = $this->readFilter($request, $path);
        } elseif ($name === 'sorter') {
            $value = $this->readSorter($request, $component, $path);
        } else {
            $value = $encodedExtSorter = $request->query->get($path, null, true);
        }

        $result = parent::readAttribute($name, $value, $component, $persistent);

        return null !== $result
            ? $result
            : $default
        ;
    }

    protected function readFilter(Request $request, $path)
    {
        $encodedExtFilter = $request->query->get($path, '[]', true);
        $decodedExtFilter = json_decode($encodedExtFilter, true);
        if (count($decodedExtFilter)) {
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

    protected function readSorter(Request $request, $component = null, $path)
    {
        $encodedExtSorter = $request->query->get($path, '[]', true);
        $decodedExtSorter = json_decode($encodedExtSorter, true);

        $value = [];
        if (is_array($decodedExtSorter)) {
            if (count($decodedExtSorter)) {
                foreach ($decodedExtSorter as $sorter) {
                    $value[$sorter['property']] = $sorter['direction'];
                }
            }
        } else {
            $directionPath = $this->createAttributePath('dir', $component);
            $value[$encodedExtSorter] = $request->query->get($directionPath, SorterRule::ASC);
        }

        return count($value) ? $value : null;
    }

    protected function createAttributePath($attributeName, $component = null)
    {
        $extAttributeName = null;
        switch ($attributeName) {
            case 'sorter':
                $extAttributeName = 'sort';
                break;
            default:
                $extAttributeName = $attributeName;
                break;
        }

        if ($component) {
            return $component . '[' . $extAttributeName . ']';
        }

        return $extAttributeName;
    }
}
