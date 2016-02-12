<?php

use DatastoreHelper\AllocateIds;
use DatastoreHelper\BaseEntityModel;
use DatastoreHelper\Commit;
use DatastoreHelper\Commit\Mutation;
use DatastoreHelper\Key;
use DatastoreHelper\Lookup;
use DatastoreHelper\Query;
use DatastoreHelper\Query\Order\Direction;
use DatastoreHelper\Query\Projection\AggregationFunction;
use DatastoreHelper\Query\Filter\Composite;
use DatastoreHelper\Query\Filter\Property;
use DatastoreHelper\ReadOptions;
use DatastoreHelper\Transaction\IsolationLevel;
use DatastoreHelper\Type;

class DatastoreHelper
{
    /**
     * @var \Google_Service_Datastore $datastoreService
     */
    protected $datastoreService;

    /**
     * @var string $datasetId
     */
    protected $datasetId;

    /**
     * DatastoreHelper constructor.
     *
     * @param \Google_Service_Datastore $datastoreService
     * @param string $datasetId
     */
    public function __construct($datastoreService, $datasetId = '')
    {
        $this->datastoreService = $datastoreService;
        $this->datasetId = $datasetId;
    }

    /**
     * @return string
     */
    public function getDatasetId()
    {
        return $this->datasetId;
    }

    /**
     * @param string $datasetId
     */
    public function setDatasetId($datasetId)
    {
        $this->datasetId = $datasetId;
    }

    /**
     * @param Google_Service_Datastore_Key|Google_Service_Datastore_Key[] $key_or_keys
     * @param array $options
     * @return AllocateIds\Results
     */
    public function allocateKeys($key_or_keys, $options = [])
    {
        if (!is_array($key_or_keys))
            $key_or_keys = [$key_or_keys];

        $request = new Google_Service_Datastore_AllocateIdsRequest;
        $request->setKeys($key_or_keys);

        $response = $this->datastoreService->datasets->allocateIds($this->datasetId, $request, $options);

        return new AllocateIds\Results($response);
    }

    /**
     * @param string $isolationLevel
     * @param array $options
     * @return string
     */
    public function beginTransaction($isolationLevel = IsolationLevel::_SNAPSHOT, $options = [])
    {
        $request = new Google_Service_Datastore_BeginTransactionRequest;
        $request->setIsolationLevel(IsolationLevel::get($isolationLevel)->value());

        $response = $this->datastoreService->datasets->beginTransaction($this->datasetId, $request, $options);

        return $response->getTransaction();
    }

        /**
     * @param Google_Service_Datastore_Mutation|Mutation\Builder $mutation
     * @param string $transaction
     * @param bool $ignoreReadOnly
     * @param array $options
     * @return Commit\Results
     */
    public function commitMutation($mutation, $transaction = null, $ignoreReadOnly = false, $options = [])
    {
        if ($mutation instanceof Mutation\Builder)
            $mutation = $mutation->build();

        $request = new Google_Service_Datastore_CommitRequest;
        $request->setMutation($mutation);

        if ($transaction === null) $request->setTransaction($transaction);
        $request->setMode($transaction === null ? Commit\Mode::_NON_TRANSACTIONAL : Commit\Mode::_TRANSACTIONAL);

        $request->setIgnoreReadOnly($ignoreReadOnly);

        $response = $this->datastoreService->datasets->commit($this->datasetId, $request, $options);

        return new Commit\Results($response);
    }

    /**
     * @param Google_Service_Datastore_Key|Google_Service_Datastore_Key[] $key_or_keys
     * @param \Google_Service_Datastore_ReadOptions|ReadOptions\Builder $readOptions
     * @param array $options
     * @return Lookup\Results
     */
    public function lookup($key_or_keys, $readOptions = null, $options = [])
    {
        if (!is_string($key_or_keys))
            $key_or_keys = [$key_or_keys];

        $request = new Google_Service_Datastore_LookupRequest;
        $request->setKeys($key_or_keys);

        if ($readOptions !== null) {
            if ($readOptions instanceof ReadOptions\Builder)
                $readOptions = $readOptions->build();

            $request->setReadOptions($readOptions->build());
        }

        $response = $this->datastoreService->datasets->lookup($this->datasetId, $request, $options);

        return new Lookup\Results($response);
    }

    /**
     * On failure, a `\Google_Exception` will be thrown
     *
     * @param string $transaction
     * @param array $options
     * @return bool
     */
    public function rollback($transaction, $options = [])
    {
        $request = new Google_Service_Datastore_RollbackRequest();
        $request->setTransaction($transaction);

        $this->datastoreService->datasets->rollback($this->datasetId, $request, $options);

        return true;
    }

    /**
     * @param \Google_Service_Datastore_Query|Query\Builder $query
     * @param array $options
     * @return Query\Results
     */
    public function runQuery($query, $options = [])
    {
        if ($query instanceof Query\Builder)
            $query = $query->build();

        // wrap the query in a run query request
        $request = new \Google_Service_Datastore_RunQueryRequest;
        $request->setQuery($query);

        // run the actual query
        $response = $this->datastoreService->datasets->runQuery($this->datasetId, $request, $options);

        // wrap the response in a helper class
        return new Query\Results($response);
    }

    /**
     * @param mixed $value
     * @param Type|string $type
     * @param boolean $indexed
     * @return \Google_Service_Datastore_Property
     */
    public static function newProperty($value, $type, $indexed = true)
    {
        $property = new \Google_Service_Datastore_Property;

        if ($value instanceof \DateTime)
            $value = $value->format(DateTime::ATOM);
        else if ($value instanceof BaseEntityModel)
            $value = $value->exportEntity();

        call_user_func([$property, Type::get($type)->getFuncName('set')], $value);
        if ($indexed === true) $property->setIndexed($indexed);
        return $property;
    }

    /**
     * @param string $propertyName
     * @param AggregationFunction|string $aggregationFunction
     * @return Google_Service_Datastore_PropertyExpression
     */
    public static function newPropertyExpression($propertyName, $aggregationFunction = null)
    {
        $expression = new \Google_Service_Datastore_PropertyExpression;
        $expression->setProperty(\DatastoreHelper::newPropertyReference($propertyName));
        if ($aggregationFunction !== null) $expression->setAggregationFunction(AggregationFunction::get($aggregationFunction)->value());
        return $expression;
    }

    /**
     * @param string $propertyName
     * @return \Google_Service_Datastore_PropertyReference
     */
    public static function newPropertyReference($propertyName)
    {
        $property = new \Google_Service_Datastore_PropertyReference;
        $property->setName($propertyName);
        return $property;
    }

    /**
     * @param string $propertyName
     * @param Direction|string $direction
     * @return Google_Service_Datastore_PropertyReference
     */
    public static function newPropertyOrder($propertyName, $direction = 'ASCENDING')
    {
        $order = new \Google_Service_Datastore_PropertyOrder;
        $order->setProperty(\DatastoreHelper::newPropertyReference($propertyName));
        $order->setDirection(Direction::get($direction)->value());
        return $order;
    }

    /**
     * @param mixed $datum
     * @param Type|string $type
     * @return Google_Service_Datastore_Value
     */
    public static function newValue($datum, $type)
    {
        $value = new \Google_Service_Datastore_Value;
        call_user_func([$value, Type::get($type)->getFuncName('set')], $datum);
        return $value;
    }

    /**
     * @param string $kindName
     * @return \Google_Service_Datastore_KindExpression
     */
    public static function newKindExpression($kindName)
    {
        $kind = new \Google_Service_Datastore_KindExpression;
        $kind->setName($kindName);
        return $kind;
    }

}