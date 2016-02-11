<?php

namespace DatastoreHelper\Key;

use DatastoreHelper\Exception\MissingFieldsException;
use DatastoreHelper\Exception\ParameterException;
use DatastoreHelper\Interfaces\IBuilder;

class Builder implements IBuilder
{
    /**
     * @var string $datasetId
     */
    protected $datasetId = null;

    /**
     * @var string $namespace
     */
    protected $namespace = null;

    /**
     * @var \Google_Service_Datastore_KeyPathElement[] $path
     */
    protected $path = [];

    /**
     * Builder constructor.
     *
     * @param string $datasetId
     * @param string $namespace
     * @param \Google_Service_Datastore_KeyPathElement|\Google_Service_Datastore_KeyPathElement[] $path_or_paths
     */
    public function __construct($datasetId = null, $namespace = null, $path_or_paths = null)
    {
        if ($datasetId !== null) $this->withDatasetId($datasetId);
        if ($namespace !== null) $this->withNamespace($namespace);
        if ($path_or_paths !== null) $this->withPath($path_or_paths);
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
     * @return $this
     */
    public function withDatasetId($datasetId)
    {
        $this->datasetId = $datasetId;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     * @return $this
     */
    public function withNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param Path\Builder|Path\Builder[]|\Google_Service_Datastore_KeyPathElement|\Google_Service_Datastore_KeyPathElement[] $path_or_paths
     * @return $this
     * @throws ParameterException
     */
    public function withPath($path_or_paths)
    {
        if (!is_array($path_or_paths))
            $path_or_paths = [$path_or_paths];

        foreach ($path_or_paths as $path) {
            if ($path instanceof Path\Builder)
                $this->path[] = $path->build();
            else if ($path instanceof \Google_Service_Datastore_KeyPathElement)
                $this->path[] = $path;
            else
                throw new ParameterException(__METHOD__, [Path\Builder::class, \Google_Service_Datastore_KeyPathElement::class]);
        }

        return $this;
    }

    /**
     * @return \Google_Service_Datastore_Key
     * @throws MissingFieldsException
     */
    public function build()
    {
        $missing = [];
        if ($this->datasetId === null) $missing[] = '$datasetId';
        if (empty($this->path)) $missing[] = '$path';

        if (!empty($missing))
            throw new MissingFieldsException(self::class, $missing);

        $partitionId = new \Google_Service_Datastore_PartitionId;
        $partitionId->setDatasetId($this->datasetId);
        $partitionId->setNamespace($this->namespace);

        $key = new \Google_Service_Datastore_Key;
        $key->setPartitionId($partitionId);
        $key->setPath($this->path);

        return $key;
    }
}
