<?php

namespace DatastoreHelper\Query\Filter\Property;

use DatastoreHelper\Exception\MissingFieldsException;
use DatastoreHelper\Exception\PropertyFilterOperatorException;
use DatastoreHelper\Query\Filter;
use DatastoreHelper\Type;

class Builder extends Filter\Builder
{
    /**
     * @var \Google_Service_Datastore_PropertyReference $property
     */
    protected $property;

    /**
     * @var \Google_Service_Datastore_Value $value
     */
    protected $value;

    /**
     * Builder constructor.
     *
     * @param \Google_Service_Datastore_PropertyReference|string $propertyName
     * @param string $operator
     * @param string $value
     * @param string $valueType
     */
    public function __construct($propertyName = null, $operator = null, $value = null, $valueType = null)
    {
        if ($propertyName !== null) $this->withProperty($propertyName);
        if ($operator !== null) $this->withOperator($operator);
        if ($value !== null) $this->withValue($value, $valueType);
    }

    /**
     * @return \Google_Service_Datastore_PropertyReference
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * @param \Google_Service_Datastore_PropertyReference|string $property
     * @return $this
     */
    public function withProperty($property)
    {
        if (!$property instanceof \Google_Service_Datastore_PropertyReference)
            $property = \DatastoreHelper::newPropertyReference($property);

        $this->property = $property;
        return $this;
    }

    /**
     * @param Operator|string $operator
     * @return $this
     * @throws PropertyFilterOperatorException
     */
    public function withOperator($operator)
    {
        $this->operator = Operator::get($operator);
        return $this;
    }

    /**
     * @return \Google_Service_Datastore_Value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param \Google_Service_Datastore_Value|mixed $value
     * @param Type|string $type
     * @return $this
     */
    public function withValue($value, $type = null)
    {
        if (!$value instanceof \Google_Service_Datastore_Value)
            $value = \DatastoreHelper::newValue($value, $type);

        $this->value = $value;
        return $this;
    }

    /**
     * @return \Google_Service_Datastore_Filter
     * @throws MissingFieldsException
     */
    public function build()
    {
        $propertyFilter = new \Google_Service_Datastore_PropertyFilter;

        $missing = [];
        if (empty($this->property)) $missing[] = '$propertyName';
        if (empty($this->operator)) $missing[] = '$operator';
        if (empty($this->value))    $missing[] = '$value';

        if (!empty($missing))
            throw new MissingFieldsException(self::class, $missing);

        $propertyFilter->setProperty($this->property);
        $propertyFilter->setOperator($this->operator->value());
        $propertyFilter->setValue($this->value);

        $filter = new \Google_Service_Datastore_Filter;
        $filter->setPropertyFilter($propertyFilter);

        return $filter;
    }
}
