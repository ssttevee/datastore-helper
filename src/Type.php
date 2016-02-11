<?php

namespace DatastoreHelper;

use DatastoreHelper\Exception\DatastoreHelperException;
use DatastoreHelper\Exception\PropertyTypeException;

class Type extends BaseEnum
{
    const _BLOB_KEY = 'blobKey';
    const _BLOB = 'blob';
    const _BOOLEAN = 'boolean';
    const _DATE_TIME = 'dateTime';
    const _DOUBLE = 'double';
    const _ENTITY = 'entity';
    const _INTEGER = 'integer';
    const _KEY = 'key';
    const _LIST = 'list';
    const _STRING = 'string';

    public function getName()
    {
        return 'Property type';
    }

    public function getExceptionClass()
    {
        return PropertyTypeException::class;
    }

    /**
     * @param string $operation
     * @return string
     * @throws DatastoreHelperException
     */
    public function getFuncName($operation = 'get')
    {
        if ($operation !== 'get' && $operation !== 'set')
            throw new DatastoreHelperException('Operation, `' .$operation . '`, is not allowed. Acceptable operations are `get` and `set`.');

        switch($this->value()) {
            case Type::_BLOB_KEY:
                return $operation . 'BlobKeyValue';
            case Type::_BLOB:
                return $operation . 'BlobValue';
            case Type::_BOOLEAN:
                return $operation . 'BooleanValue';
            case Type::_DATE_TIME:
                return $operation . 'DateTimeValue';
            case Type::_DOUBLE:
                return $operation . 'DoubleValue';
            case Type::_ENTITY:
                return $operation . 'EntityValue';
            case Type::_INTEGER:
                return $operation . 'IntegerValue';
            case Type::_KEY:
                return $operation . 'KeyValue';
            case Type::_LIST:
                return $operation . 'ListValue';
            case Type::_STRING:
                return $operation . 'StringValue';
        }
    }

    /**
     * @param Type|string $value
     * @return Type
     */
    public static function get($value)
    {
        return parent::get($value);
    }
}