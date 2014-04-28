<?php

namespace Imatic\Bundle\DataBundle\Data\Query\DisplayCriteria;

class FilterOperatorMap
{
    const OPERATOR_EQUAL = 'eq';

    const OPERATOR_NOT_EQUAL = 'neq';

    const OPERATOR_CONTAINS = 'like';

    const OPERATOR_NOT_CONTAINS = 'notLike';

    const OPERATOR_EMPTY = 'isNull';

    const OPERATOR_NOT_EMPTY = 'isNotNull';

    const OPERATOR_GREATER = 'gt';

    const OPERATOR_GREATER_EQUAL = 'gte';

    const OPERATOR_LESSER = 'lt';

    const OPERATOR_LESSER_EQUAL = 'lte';

    const OPERATOR_BETWEEN = 'between';

    const OPERATOR_NOT_BETWEEN = 'notBetween';

    const OPERATOR_IN = 'in';

    const OPERATOR_NOT_IN = 'notIn';

    protected static $filterOperators = [
        self::FILTER_BOOL => [
            self::OPERATOR_EQUAL,
            self::OPERATOR_EMPTY,
            self::OPERATOR_NOT_EMPTY,
        ],
        self::FILTER_DATE => [
            self::OPERATOR_EQUAL,
            self::OPERATOR_NOT_EQUAL,
            self::OPERATOR_GREATER,
            self::OPERATOR_GREATER_EQUAL,
            self::OPERATOR_LESSER,
            self::OPERATOR_LESSER_EQUAL,
            self::OPERATOR_EMPTY,
            self::OPERATOR_NOT_EMPTY,
        ],
        self::FILTER_RANGE => [
            self::OPERATOR_BETWEEN,
            self::OPERATOR_NOT_BETWEEN,
        ],
        self::FILTER_CHOICE => [
            self::OPERATOR_IN,
            self::OPERATOR_NOT_IN,
        ],
    ];
}
