<?php

namespace DatastoreHelper;

use DatastoreHelper\Exception\DatastoreHelperException;

abstract class SimpleEntityModel extends BaseEntityModel
{
    /**
     * @var array $definedProperties
     */
    private $definedProperties = [];

    /**
     * @var mixed[] $properties
     */
    protected $properties = [];

    /**
     * SimpleEntityModel constructor.
     * @param \Google_Service_Datastore_Entity $entity
     */
    public function __construct($entity = null)
    {
        $this->definedProperties = [];
        $this->defineProperties();

        parent::__construct($entity);
    }

    /**
     * @param $name
     * @return mixed
     * @throws DatastoreHelperException
     */
    public function __get($name)
    {
        if (isset($this->definedProperties[$name]))
            return @$this->properties[$name];

        throw new DatastoreHelperException('Property `' . $name . '` was not defined.');
    }

    /**
     * @param string $name
     * @param $value
     * @return
     * @throws DatastoreHelperException
     */
    public function __set($name, $value)
    {
        if (isset($this->definedProperties[$name]))
            return $this->properties[$name] = $value;

        throw new DatastoreHelperException('Property `' . $name . '` was not defined.');
    }

    /**
     * Define a property
     * @param string $name
     * @param Type|string $type
     * @param boolean $indexed
     * @throws DatastoreHelperException
     */
    public function defineProperty($name, $type, $indexed = false)
    {
        if (isset($this->definedProperties[$name]))
            throw new DatastoreHelperException('Property `' . $name . '` has already been defined.');

        $this->definedProperties[$name] = [
            'type' => Type::get($type),
            'indexed' => $indexed
        ];
    }

    /**
     * @param \Google_Service_Datastore_Property[] $properties
     * @throws DatastoreHelperException
     */
    protected function extractProperties($properties)
    {
        if (empty($this->definedProperties))
            throw new DatastoreHelperException('No properties were defined in ' . get_class($this));

        foreach ($this->definedProperties as $name => $spec) {
            if (isset($properties[$name]))
                $this->$name = call_user_func([$properties[$name], $spec['type']->getFuncName('get')]);
        }
    }

    /**
     * @return \Google_Service_Datastore_Entity
     * @throws DatastoreHelperException
     */
    public function exportEntity()
    {
        if (empty($this->definedProperties))
            throw new DatastoreHelperException('No properties were defined in ' . self::class);

        $entity = new \Google_Service_Datastore_Entity();
        $entity->setKey($this->getKey());

        $properties = [];
        foreach ($this->definedProperties as $name => $spec) {
            $properties[$name] = \DatastoreHelper::newProperty($name, $spec['type'], $spec['indexed']);
        }

        $entity->setProperties($properties);

        return $entity;
    }

    /**
     * Specify the names and types of each property in the kind
     * @see definePropertyType
     * @return void
     */
    abstract protected function defineProperties();
}
