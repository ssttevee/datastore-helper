<?php

namespace DatastoreHelper\Exception;

use DatastoreHelper\BaseEnum;

class EnumException extends DatastoreHelperException
{
    /**
     * EnumException constructor.
     * @param BaseEnum $enum
     * @param mixed $value
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($enum, $value, $code = 0, \Exception $previous = null)
    {
        parent::__construct($enum->getName() . ', `' . $value . '`, is not allowed.  Acceptable values are: `' . implode('`, `', $enum::getValues()) . '`.', $code, $previous);
    }
}
