<?php

namespace DatastoreHelper\Commit;

use DatastoreHelper\BaseEnum;
use DatastoreHelper\Exception\CommitModeException;

class Mode extends BaseEnum
{
    const _NON_TRANSACTIONAL = 'NON_TRANSACTIONAL';
    const _TRANSACTIONAL = 'TRANSACTIONAL';

    public function getName()
    {
        return 'Commit mode';
    }

    public function getExceptionClass()
    {
        return CommitModeException::class;
    }


}
