<?php

namespace DatastoreHelper\Query;

use DatastoreHelper\BaseEntityModel;
use DatastoreHelper\BaseEntityResults;
use DatastoreHelper\Key;

class Results extends BaseEntityResults
{
    /**
     * @var string $endCursor
     */
    protected $endCursor;

    /**
     * @var string $entityResultType
     */
    protected $entityResultType;

    /**
     * @var string $moreResults
     */
    protected $moreResults;

    /**
     * @var integer $skippedResults
     */
    protected $skippedResults;

    /**
     * Results constructor.
     *
     * @param \Google_Service_Datastore_RunQueryResponse $response
     */
    public function __construct($response)
    {
        $batch = $response->getBatch();

        $this->endCursor = $batch->getEndCursor();
        $this->entityResultType = $batch->getEntityResultType();
        $this->moreResults = $batch->getMoreResults();
        $this->skippedResults = $batch->getSkippedResults();

        $batch->getEntityResults();

        $this->entities = [];
        /** @var \Google_Service_Datastore_EntityResult $entityResult */
        foreach($batch as $entityResult) {
            $this->entities[] = $entityResult->getEntity();
        }
    }

    /**
     * Get all of the returned entities without trying to fit them into models
     *
     * @return \Google_Service_Datastore_Entity[]
     */
    public function getRawEntities()
    {
        return $this->entities;
    }

    /**
     * Get all of the returned entities
     *
     * @return \Google_Service_Datastore_Entity[]|BaseEntityModel[]|mixed[]
     */
    public function getEntities()
    {
        $entities = [];

        foreach ($this as $entity) {
            $entities[] = $entity;
        }

        return $entities;
    }

    /**
     * A cursor that points to the position after the last result in the batch. May be absent.
     *
     * @return string
     */
    public function getEndCursor()
    {
        return $this->endCursor;
    }

    /**
     * The result type for every entity in entityResults. full for full entities, projection for entities with only projected properties, keyOnly for entities with only a key.
     *
     * Acceptable values are: `FULL`, `KEY_ONLY`, `PROJECTION`
     *
     * @return string
     */
    public function getEntityResultType()
    {
        return $this->entityResultType;
    }

    /**
     * The state of the query after the current batch. One of notFinished, moreResultsAfterLimit, noMoreResults.
     *
     * Acceptable values are: `MORE_RESULTS_AFTER_LIMIT`, `NOT_FINISHED`, `NO_MORE_RESULTS`
     *
     * Note: In my testing, it always returns `MORE_RESULTS_AFTER_LIMIT` even when not using a limit
     *
     * @return bool
     */
    public function getMoreResults() {
        return $this->moreResults;
    }

    /**
     * The number of results skipped because of Query.offset.
     *
     * @return integer
     */
    public function getSkippedResults()
    {
        return $this->skippedResults;
    }
}