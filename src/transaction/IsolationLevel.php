<?php

namespace DatastoreHelper\Transaction;

use DatastoreHelper\BaseEnum;
use DatastoreHelper\Exception\IsolationLevelException;

class IsolationLevel extends BaseEnum
{
    const _SNAPSHOT = 'SNAPSHOT';
    const _SERIALIZABLE = 'SERIALIZABLE';

    public function getName()
    {
        return 'Transaction isolation level';
    }

    public function getExceptionClass()
    {
        return IsolationLevelException::class;
    }
}