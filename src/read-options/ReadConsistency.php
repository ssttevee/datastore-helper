<?php

namespace DatastoreHelper\ReadOptions;

use DatastoreHelper\BaseEnum;
use DatastoreHelper\Exception\ReadConsistencyException;

class ReadConsistency extends BaseEnum
{
    const _DEFAULT = 'DEFAULT';
    const _EVENTUAL = 'EVENTUAL';
    const _STRONG = 'STRONG';

    public function getName()
    {
        return 'Read consistency';
    }

    public function getExceptionClass()
    {
        return ReadConsistencyException::class;
    }
}
