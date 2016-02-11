<?php

namespace DatastoreHelper\Exception;

class ParameterException extends \Exception
{
    /**
     * ParameterException constructor.
     * @param string $methodName
     * @param array $allowedClasses
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($methodName, $allowedClasses, $code = 0, \Exception $previous = null)
    {
        $message = 'An object passed to `' . $methodName . '` is not allowed; allowed classes are: `' . implode('`, `', $allowedClasses) . '`';

        parent::__construct($message, $code, $previous);
    }
}
