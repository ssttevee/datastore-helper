<?php

namespace DatastoreHelper\Query;

use DatastoreHelper\Exception\ParameterException;
use DatastoreHelper\Query\Filter;
use DatastoreHelper\Interfaces\IBuilder;
use DatastoreHelper\Query\Order\Direction;

class Builder implements IBuilder
{
    /**
     * @var \Google_Service_Datastore_PropertyExpression[] $projections
     */
    protected $projections = [];

    /**
     * @var \Google_Service_Datastore_KindExpression[] $kinds
     */
    protected $kinds = [];

    /**
     * @var \Google_Service_Datastore_Filter $filter
     */
    protected $filter = null;

    /**
     * @var \Google_Service_Datastore_PropertyOrder[] $orders
     */
    protected $orders = [];

    /**
     * @var \Google_Service_Datastore_PropertyReference[] $groupBys
     */
    protected $groupBys = [];

    /**
     * @var string $startCursor
     */
    protected $startCursor = null;

    /**
     * @var string $endCursor
     */
    protected $endCursor = null;

    /**
     * @var integer $offset
     */
    protected $offset = null;

    /**
     * @var integer $limit
     */
    protected $limit = null;

    /**
     * @var bool $keysOnly
     */
    protected $keysOnly = false;

    /**
     * Builder constructor.
     *
     * @param \Google_Service_Datastore_KindExpression|string $kind
     */
    public function __construct($kind = null)
    {
        if ($kind === null) $this->withKind($kind);
    }

    /**
     * @return \Google_Service_Datastore_Query
     */
    public function build()
    {
        $query = new \Google_Service_Datastore_Query;

        if (!empty($this->projections))
            $query->setProjection($this->projections);

        if (!empty($this->kinds))
            $query->setKinds($this->kinds);

        if (!is_null($this->filter))
            $query->setFilter($this->filter);

        if (!empty($this->orders))
            $query->setOrder($this->orders);

        if (!empty($this->groupBys))
            $query->setGroupBy($this->groupBys);

        if (!is_null($this->startCursor))
            $query->setStartCursor($this->startCursor);

        if (!is_null($this->endCursor))
            $query->setEndCursor($this->endCursor);

        if (!is_null($this->offset))
            $query->setOffset($this->offset);

        if (!is_null($this->limit))
            $query->setLimit($this->limit);

        return $query;
    }

    /**
     * @return \Google_Service_Datastore_PropertyExpression[]
     */
    public function getProjections()
    {
        return $this->projections;
    }

    /**
     * @param \Google_Service_Datastore_PropertyExpression|\Google_Service_Datastore_PropertyExpression[]|string|string[] $propertyExpression_or_propertyExperssions
     * @param string $aggregationFunction
     * @return $this
     * @throws ParameterException
     */
    public function withProjection($propertyExpression_or_propertyExperssions, $aggregationFunction = null)
    {
        // if we add any projections to a keys-only query, then it's not keys only anymore...
        if ($this->keysOnly)
            return $this;

        if (!is_array($propertyExpression_or_propertyExperssions))
            $propertyExpression_or_propertyExperssions = [$propertyExpression_or_propertyExperssions];

        foreach ($propertyExpression_or_propertyExperssions as $propertyExpression) {
            if (is_string($propertyExpression))
                $propertyExpression = \DatastoreHelper::newPropertyExpression($propertyExpression, $aggregationFunction);
            else if (!$propertyExpression instanceof \Google_Service_Datastore_PropertyExpression)
                throw new ParameterException(__METHOD__, ['string', \Google_Service_Datastore_PropertyExpression::class]);

            $this->projections[] = $propertyExpression;
        }

        return $this;
    }

    /**
     * @return \Google_Service_Datastore_KindExpression[]
     */
    public function getKinds()
    {
        return $this->kinds;
    }

    /**
     * @param \Google_Service_Datastore_KindExpression|\Google_Service_Datastore_KindExpression[]|string|string[] $kind_or_kinds
     * @return $this
     * @throws ParameterException
     */
    public function withKind($kind_or_kinds)
    {
        if(!is_array($kind_or_kinds))
            $kind_or_kinds = [$kind_or_kinds];

        foreach ($kind_or_kinds as $kind) {
            if (is_string($kind))
                $kind = \DatastoreHelper::newKindExpression($kind);
            else if (!$kind instanceof \Google_Service_Datastore_KindExpression)
                throw new ParameterException(__METHOD__, ['string', \Google_Service_Datastore_KindExpression::class]);

            $this->kinds[] = $kind;
        }

        return $this;
    }

    /**
     * @return \Google_Service_Datastore_Filter
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param \Google_Service_Datastore_Filter|Filter\Builder $filter
     * @return $this
     * @throws ParameterException
     */
    public function withFilter($filter)
    {
        if ($filter instanceof Filter\Builder)
            $filter = $filter->build();
        else if (!$filter instanceof \Google_Service_Datastore_Filter)
            throw new ParameterException(__METHOD__, [Filter\Builder::class, \Google_Service_Datastore_Filter::class]);

        $this->filter = $filter;
        return $this;
    }

    /**
     * @return \Google_Service_Datastore_PropertyOrder[]
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param \Google_Service_Datastore_PropertyOrder|\Google_Service_Datastore_PropertyOrder[]|string|string[] $order_or_orders
     * @param Direction|string $direction
     * @return $this
     * @throws ParameterException
     */
    public function withOrder($order_or_orders, $direction = 'ASCENDING')
    {
        if(!is_array($order_or_orders))
            $order_or_orders = [$order_or_orders];

        foreach ($order_or_orders as $order) {
            if (is_string($order))
                $order = \DatastoreHelper::newPropertyOrder($order, $direction);
            else if (!$order instanceof \Google_Service_Datastore_PropertyOrder)
                throw new ParameterException(__METHOD__, ['string', \Google_Service_Datastore_PropertyOrder::class]);

            $this->orders[] = $order;
        }

        return $this;
    }

    /**
     * @return \Google_Service_Datastore_PropertyReference[]
     */
    public function getGroupBys()
    {
        return $this->groupBys;
    }

    /**
     * @param \Google_Service_Datastore_PropertyReference|\Google_Service_Datastore_PropertyReference[]|string|string[] $property_or_properties
     * @return $this
     * @throws ParameterException
     */
    public function withGroupBy($property_or_properties)
    {
        if(!is_array($property_or_properties))
            $property_or_properties = [$property_or_properties];

        foreach ($property_or_properties as $property) {
            if (is_string($property))
                $property = \DatastoreHelper::newPropertyReference($property);
            else if (!$property instanceof \Google_Service_Datastore_PropertyReference)
                throw new ParameterException(__METHOD__, ['string', \Google_Service_Datastore_PropertyReference::class]);

            $this->groupBys[] = $property;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getStartCursor()
    {
        return $this->startCursor;
    }

    /**
     * @param string $startCursor
     * @return $this
     */
    public function withStartCursor($startCursor)
    {
        $this->startCursor = $startCursor;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndCursor()
    {
        return $this->endCursor;
    }

    /**
     * @param string $endCursor
     * @return $this
     */
    public function withEndCursor($endCursor)
    {
        $this->endCursor = $endCursor;
        return $this;
    }

    /**
     * @return integer
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param integer $offset
     * @return $this
     */
    public function withOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param integer $limit
     * @return $this
     */
    public function withLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Set keys only
     *
     * A keys-only query returns just the keys of the result entities instead of the entities themselves, at lower latency and cost than retrieving entire entities.
     * It is often more economical to do a keys-only query first, and then fetch a subset of entities from the results, rather than executing a general query which may fetch more entities than you actually need.
     *
     * @return $this
     */
    public function withKeysOnly()
    {
        $this->projections = [];
        $this->withProjection('__key__');
        $this->keysOnly = true;
        return $this;
    }
}