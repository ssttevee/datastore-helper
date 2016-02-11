<?php

namespace DatastoreHelper;

use DatastoreHelper\Exception\EnumException;

abstract class BaseEnum
{
    private static $constCache = null;

    private $value;

    public function __construct($initial_value)
    {
        foreach (self::getConstants() as $constant => $value)
        {
            if ($initial_value == $value) {
                $this->value = $value;
                return;
            }
        }
        $exceptionClass = $this->getExceptionClass();
        throw new $exceptionClass($this, $initial_value);
    }

    public function value()
    {
        return $this->value;
    }

    /**
     * Pretty name used in enum exceptions
     *
     * @return string
     */
    public function getName()
    {
        return get_class($this);
    }

    /**
     * The exception class
     *
     * @return string
     */
    public function getExceptionClass()
    {
        return EnumException::class;
    }

    /**
     * @param BaseEnum|mixed $value
     * @return BaseEnum
     */
    public static function get($value)
    {
        if (!$value instanceof BaseEnum) {
            $class = get_called_class();
            return new $class($value);
        }

        return $value;
    }

    public static function getConstants()
    {
        if (self::$constCache === null)
            self::$constCache = [];

        $class = get_called_class();
        if (!array_key_exists($class, self::$constCache))
            self::$constCache[$class] = (new \ReflectionClass($class))->getConstants();

        return self::$constCache[$class];
    }

    public static function getValues()
    {
        return array_values(self::getConstants());
    }
}
