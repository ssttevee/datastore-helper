<?php

namespace DatastoreHelper\Query\Filter\Property;

use DatastoreHelper\Query\Filter;
use DatastoreHelper\Exception\PropertyFilterOperatorException;

class Operator extends Filter\Operator
{
    const _EQUAL = 'EQUAL';
    const _GREATER_THAN = 'GREATER_THAN';
    const _GREATER_THAN_OR_EQUAL = 'GREATER_THAN_OR_EQUAL';
    const _HAS_ANCESTOR = 'HAS_ANCESTOR';
    const _LESS_THAN = 'LESS_THAN';
    const _LESS_THAN_OR_EQUAL = 'LESS_THAN_OR_EQUAL';

    public function getName()
    {
        return 'Property filter operator';
    }

    public function getExceptionClass()
    {
        return PropertyFilterOperatorException::class;
    }
}
