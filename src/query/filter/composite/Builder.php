<?php

namespace DatastoreHelper\Query\Filter\Composite;

use DatastoreHelper\Exception\MissingFieldsException;
use DatastoreHelper\Exception\ParameterException;
use DatastoreHelper\Query\Filter;

class Builder extends Filter\Builder
{
    /**
     * @var \Google_Service_Datastore_Filter[] $filters
     */
    protected $filters = [];

    /**
     * Builder constructor.
     *
     * @param \Google_Service_Datastore_Filter|\Google_Service_Datastore_Filter[] $filter_or_filters
     * @param string $operator
     */
    public function __construct($filter_or_filters, $operator)
    {
        if ($filter_or_filters !== null) $this->withFilter($filter_or_filters);
        if ($operator !== null) $this->withOperator($operator);
    }

    /**
     * @return \Google_Service_Datastore_Filter[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param \Google_Service_Datastore_Filter|\Google_Service_Datastore_Filter[]|Filter\Builder|Filter\Builder[] $filter_or_filters
     * @return $this
     * @throws ParameterException
     */
    public function withFilter($filter_or_filters)
    {
        if (!is_array($filter_or_filters))
            $filter_or_filters = [$filter_or_filters];

        foreach ($filter_or_filters as $filter)
        {
            if ($filter instanceof Filter\Builder)
                $this->filters[] = $filter->build();
            else if ($filter instanceof \Google_Service_Datastore_Filter)
                $this->filters[] = $filter;
            else
                throw new ParameterException(__METHOD__, [Filter\Builder::class, \Google_Service_Datastore_Filter::class]);
        }

        return $this;
    }

    /**
     * @param Operator|string $operator
     * @return $this
     */
    public function withOperator($operator)
    {
        $this->operator = Operator::get($operator);
        return $this;
    }

    /**
     * @return \Google_Service_Datastore_Filter
     * @throws MissingFieldsException
     */
    public function build()
    {
        $compositeFilter = new \Google_Service_Datastore_CompositeFilter;

        $missing = [];
        if (empty($this->filters))  $missing[] = '$filter';
        if (empty($this->operator)) $missing[] = '$operator';

        if (!empty($missing))
            throw new MissingFieldsException(self::class, $missing);

        $compositeFilter->setFilters($this->filters);
        $compositeFilter->setOperator($this->operator);

        $filter = new \Google_Service_Datastore_Filter;
        $filter->setCompositeFilter($compositeFilter);

        return $filter;
    }
}
