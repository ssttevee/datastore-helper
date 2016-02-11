<?php

namespace DatastoreHelper\Commit\Mutation;

use DatastoreHelper\Exception\MissingFieldsException;
use DatastoreHelper\Interfaces\IBuilder;

class Builder implements IBuilder
{
    /**
     * @var \Google_Service_Datastore_Entity[] $upsert
     */
    protected $upsert = [];

    /**
     * @var \Google_Service_Datastore_Entity[] $update
     */
    protected $update = [];

    /**
     * @var \Google_Service_Datastore_Entity[] $insert
     */
    protected $insert = [];

    /**
     * @var \Google_Service_Datastore_Entity[] $insertAutoId
     */
    protected $insertAutoId = [];

    /**
     * @var \Google_Service_Datastore_Key[] $delete
     */
    protected $delete = [];

    /**
     * @var boolean $force
     */
    protected $force = false;

    /**
     * @return \Google_Service_Datastore_Mutation
     * @throws MissingFieldsException
     */
    public function build()
    {
        if (empty($this->upsert) && empty($this->update) && empty($this->insert) && empty($this->insertAutoId) && empty($this->delete))
            throw new MissingFieldsException(self::class, ['$upsert', '$update', '$insert', '$insertAutoId', '$delete']);

        $mutation = new \Google_Service_Datastore_Mutation;

        if (!empty($this->upsert))
            $mutation->setUpsert($this->upsert);

        if (!empty($this->update))
            $mutation->setUpdate($this->update);

        if (!empty($this->insert))
            $mutation->setInsert($this->insert);

        if (!empty($this->insertAutoId))
            $mutation->setInsertAutoId($this->insertAutoId);

        if (!empty($this->delete))
            $mutation->setDelete($this->delete);

        $mutation->setForce($this->force);

        return $mutation;
    }

    protected function add($field, $value_or_values)
    {
        if (!is_array($value_or_values))
            $value_or_values = [$value_or_values];

        foreach ($value_or_values as $value)
            $this->$field[] = $value;

        return $this;
    }

    /**
     * @return \Google_Service_Datastore_Entity[]
     */
    public function getUpsert()
    {
        return $this->upsert;
    }

    /**
     * @param \Google_Service_Datastore_Entity|\Google_Service_Datastore_Entity[] $entity_or_entities
     * @return $this
     */
    public function withUpsert($entity_or_entities)
    {
        return $this->add('upsert', $entity_or_entities);
    }

    /**
     * @return \Google_Service_Datastore_Entity[]
     */
    public function getUpdate()
    {
        return $this->update;
    }

    /**
     * @param \Google_Service_Datastore_Entity|\Google_Service_Datastore_Entity[] $entity_or_entities
     * @return $this
     */
    public function withUpdate($entity_or_entities)
    {
        return $this->add('update', $entity_or_entities);
    }

    /**
     * @return \Google_Service_Datastore_Entity[]
     */
    public function getInsert()
    {
        return $this->insert;
    }

    /**
     * @param \Google_Service_Datastore_Entity|\Google_Service_Datastore_Entity[] $entity_or_entities
     * @return $this
     */
    public function withInsert($entity_or_entities)
    {
        return $this->add('insert', $entity_or_entities);
    }

    /**
     * @return \Google_Service_Datastore_Entity[]
     */
    public function getInsertAutoId()
    {
        return $this->insertAutoId;
    }

    /**
     * @param \Google_Service_Datastore_Entity|\Google_Service_Datastore_Entity[] $entity_or_entities
     * @return $this
     */
    public function withInsertAutoId($entity_or_entities)
    {
        return $this->add('insertAutoId', $entity_or_entities);
    }

    /**
     * @return \Google_Service_Datastore_Key[]
     */
    public function getDelete()
    {
        return $this->delete;
    }

    /**
     * @param \Google_Service_Datastore_Key|\Google_Service_Datastore_Key[] $key_or_keys
     * @return $this
     */
    public function withDelete($key_or_keys)
    {
        return $this->add('delete', $key_or_keys);
    }
}
