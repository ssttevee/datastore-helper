<?php

namespace DatastoreHelper\AllocateIds;

class Results implements \IteratorAggregate, \Countable
{
    /**
     * @var \Google_Service_Datastore_Key[] $ids
     */
    protected $keys;

    /**
     * Results constructor.
     *
     * @param \Google_Service_Datastore_AllocateIdsResponse $response
     */
    public function __construct(\Google_Service_Datastore_AllocateIdsResponse $response)
    {
        $this->keys = $response->getKeys();
    }

    /**
     * The keys specified in the request (in the same order), each with its key path completed with a newly allocated ID.
     *
     * @return \Google_Service_Datastore_Key[]
     */
    public function getKeys()
    {
        return $this->keys;
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return \Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->keys);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->keys);
    }
}
