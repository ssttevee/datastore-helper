<?php

namespace DatastoreHelper;

use DatastoreHelper\Exception\ParameterException;

abstract class BaseEntityResults implements \Iterator, \Countable
{

    protected static $reflectionCache = [];

    /**
     * @var \ReflectionClass[] $entityModels
     */
    protected $entityModels = [];

    /**
     * @var \Google_Service_Datastore_Entity[] $entities
     */
    protected $entities = [];

    /**
     * @var integer $currentIndex
     */
    protected $currentIndex = 0;

    /**
     * @return \ReflectionClass[]
     */
    public function getEntityModels()
    {
        return $this->entityModels;
    }

    /**
     * Set the target model in which subsequent entities will be
     *
     * @param string $kind
     * @param \ReflectionClass|string $class
     * @return $this
     * @throws \Exception
     */
    public function addEntityModel($kind, $class)
    {
        $class = self::reflect($class);

        // make sure that the target class is actually an entity model class
        if (!$class->isSubclassOf(BaseEntityModel::class))
            throw new \Exception('Target model, `' . $class . '`, is not valid, it must be a subclass of `' . BaseEntityModel::class . '`');

        $this->entityModels[$kind] = $class;

        return $this;
    }

    /**
     * @param \ReflectionClass|string $class
     * @return \ReflectionClass
     * @throws ParameterException
     */
    protected static function reflect($class)
    {
        if (is_string($class)) {
            if (!isset(self::$reflectionCache[$class]))
                self::$reflectionCache[$class] = new \ReflectionClass($class);

            return self::$reflectionCache[$class];
        } else if (!$class instanceof \ReflectionClass)
            throw new ParameterException(__METHOD__, ['string', \ReflectionClass::class]);

        if (!isset(self::$reflectionCache[$class->getName()]))
            self::$reflectionCache[$class->getName()] = $class;

        return $class;
    }

    /**
     * Get the entity at the current cursor position
     *
     * @return BaseEntityModel
     */
    public function current()
    {
        /** @var \Google_Service_Datastore_Entity $entity */
        $entity = $this->entities[$this->currentIndex];

        $kind = Key::getLastPath($entity->getKey())->getKind();

        if (isset($this->entityModels[$kind]))
            return $this->entityModels[$kind]->newInstance($entity);

        return $entity;
    }

    /**
     * Move the cursor to the next position
     */
    public function next()
    {
        $this->currentIndex++;
    }

    /**
     * Get the key at the current cursor position
     *
     * @return \Google_Service_Datastore_Key
     */
    public function key()
    {
        return $this->entities[$this->currentIndex]->getKey();
    }

    /**
     * Check if the current cursor position is valid
     *
     * @return bool
     */
    public function valid()
    {
        return $this->currentIndex < count($this->entities);
    }

    /**
     * Bring the cursor back to the first position
     */
    public function rewind()
    {
        $this->currentIndex = 0;
    }

    /**
     * Count how many entities were returned
     *
     * @return integer
     */
    public function count()
    {
        return count($this->entities);
    }
}
