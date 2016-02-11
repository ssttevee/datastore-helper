<?php

namespace DatastoreHelper\Query\Projection;

use DatastoreHelper\BaseEnum;
use DatastoreHelper\Exception\ProjectionAggregationFunctionException;

class AggregationFunction extends BaseEnum
{
    const _FIRST = 'FIRST';

    public function getName()
    {
        return 'Projection aggregation function';
    }

    public function getExceptionClass()
    {
        return ProjectionAggregationFunctionException::class;
    }
}
