<?php

namespace DatastoreHelper\ReadOptions;

use DatastoreHelper\Exception\ReadOptionsException;
use DatastoreHelper\Exception\MissingFieldsException;
use DatastoreHelper\Interfaces\IBuilder;

class Builder implements IBuilder
{
    /**
     * @var ReadConsistency $readConsistency
     */
    protected $readConsistency;

    /**
     * @var string $transaction
     */
    protected $transaction;

    /**
     * @return \Google_Service_Datastore_ReadOptions
     * @throws MissingFieldsException
     * @throws ReadOptionsException
     */
    public function build()
    {
        if ($this->readConsistency === null && $this->transaction === null)
            throw new MissingFieldsException(self::class, ['$readConsistency or $transaction']);

        if ($this->readConsistency !== null && $this->transaction !== null)
            throw new ReadOptionsException('Read consistency cannot be set when transaction is set');

        $readOptions = new \Google_Service_Datastore_ReadOptions;
        if ($this->transaction !== null) $readOptions->setTransaction($this->transaction);
        if ($this->readConsistency !== null) $readOptions->setReadConsistency($this->readConsistency->value());

        return $readOptions;
    }
}
