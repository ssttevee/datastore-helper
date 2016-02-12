<?php

namespace DatastoreHelper;

abstract class BaseEntityModel
{
    /** @var \Google_Service_Datastore_Key $key */
    private $key;

    /**
     * DatastoreEntity constructor.
     * @param \Google_Service_Datastore_Entity|null $entity
     */
    public function __construct($entity = null)
    {
        if ($entity instanceof \Google_Service_Datastore_Entity) {
            $this->setKey($entity->getKey());
            $this->extractProperties($entity->getProperties());
        }
    }

    /**
     * @return \Google_Service_Datastore_Key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param \Google_Service_Datastore_Key $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @param \Google_Service_Datastore_Property[] $properties
     * @return void
     */
    abstract protected function extractProperties($properties);

    /**
     * @return \Google_Service_Datastore_Entity
     */
    abstract public function exportEntity();
}