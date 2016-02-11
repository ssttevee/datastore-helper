<?php

namespace DatastoreHelper\Commit;

use Traversable;

class Results implements \IteratorAggregate, \Countable
{
    /**
     * @var integer $indexUpdates
     */
    protected $indexUpdates;

    /**
     * @var \Google_Service_Datastore_Key[] $insertAutoIdKeys
     */
    protected $insertAutoIdKeys;

    /**
     * Results constructor.
     * @param \Google_Service_Datastore_CommitResponse $response
     */
    public function __construct($response)
    {
        /** @var \Google_Service_Datastore_MutationResult $results */
        $results = $response->getMutationResult();

        $this->indexUpdates = $results->getIndexUpdates();
        $this->insertAutoIdKeys = $results->getInsertAutoIdKeys();
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->insertAutoIdKeys);
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
        return count($this->insertAutoIdKeys);
    }
}
