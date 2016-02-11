<?php

namespace DatastoreHelper\Lookup;

use DatastoreHelper\BaseEntityResults;

class Results extends BaseEntityResults
{
    /**
     * @var \Google_Service_Datastore_Key[] $deferred
     */
    protected $deferred;

    /**
     * @var \Google_Service_Datastore_Key[] $missing
     */
    protected $missing;

    /**
     * Results constructor.
     *
     * @param \Google_Service_Datastore_LookupResponse $response
     */
    public function __construct($response)
    {
        $this->deferred = $response->getDeferred();

        $this->entities = [];
        /** @var \Google_Service_Datastore_EntityResult $foundResult */
        foreach($response->getFound() as $foundResult) {
            $this->entities[] = $foundResult->getEntity();
        }

        $this->missing = [];
        /** @var \Google_Service_Datastore_EntityResult $missingResult */
        foreach($response->getMissing() as $missingResult) {
            $this->missing[] = $missingResult->getEntity()->getKey();
        }
    }

    /**
     * @return integer
     */
    public function countDeferred()
    {
        return count($this->deferred);
    }

    /**
     * @return \Google_Service_Datastore_Key[]
     */
    public function getDeferred()
    {
        return $this->deferred;
    }

    /**
     * @return integer
     */
    public function countMissing()
    {
        return count($this->missing);
    }

    /**
     * @return \Google_Service_Datastore_Key[]
     */
    public function getMissing()
    {
        return $this->missing;
    }
}
