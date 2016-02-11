<?php

namespace DatastoreHelper\Query\Order;

use DatastoreHelper\BaseEnum;
use DatastoreHelper\Exception\OrderDirectionException;

class Direction extends BaseEnum
{
    const _DESCENDING = 'DESCENDING';
    const _ASCENDING = 'ASCENDING';

    public function getName()
    {
        return 'Query order direction';
    }

    public function getExceptionClass()
    {
        return OrderDirectionException::class;
    }
}
