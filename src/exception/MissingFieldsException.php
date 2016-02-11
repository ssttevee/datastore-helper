<?php

namespace DatastoreHelper\Exception;

class MissingFieldsException extends DatastoreHelperException
{
    /**
     * MissingFieldsException constructor.
     *
     * @param string $class
     * @param string[] $fields
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($class, $fields, $code = 0, \Exception $previous = null)
    {
        if (empty($fields))
            parent::__construct('There are no missing fields, however there may be a bug in the DatastoreHelper code.', $code, $previous);
        else
            parent::__construct($class . ' is missing the following fields: ' . implode(',', $fields), $code, $previous);
    }
}
