<?php

namespace DatastoreHelper\Query\Filter\Composite;

use DatastoreHelper\Query\Filter;
use DatastoreHelper\Exception\CompositeFilterOperatorException;

class Operator extends Filter\Operator
{
    const _AND = "AND";

    public function getName()
    {
        return 'CompositeFilter operator';
    }

    public function getExceptionClass()
    {
        return CompositeFilterOperatorException::class;
    }
}

