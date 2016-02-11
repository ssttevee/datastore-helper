<?php

namespace DatastoreHelper\Query\Filter;

use DatastoreHelper\Interfaces\IBuilder;

abstract class Builder implements IBuilder
{
    /**
     * @var Operator $operator
     */
    protected $operator;

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param Operator $operator
     * @return $this
     */
    abstract public function withOperator($operator);

    /**
     * @return \Google_Service_Datastore_Filter
     */
    abstract public function build();
}
