<?php

namespace DatastoreHelper\Key;

use DatastoreHelper\Exception\MissingFieldsException;
use DatastoreHelper\Exception\ParameterException;
use DatastoreHelper\Interfaces\IBuilder;

class Builder extends Path\Builder implements IBuilder
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
     * @var \Google_Service_Datastore_KeyPathElement[] $parent
     */
    protected $parent = [];

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

    /**
     * @return \Google_Service_Datastore_KeyPathElement[]
     */
    public function getParentPath()
    {
        return $this->parent;
    }

    /**
     * @return \Google_Service_Datastore_KeyPathElement[]
     */
    public function getPath()
    {
        $path = $this->parent;
        $path[] = parent::build();
        return $path;
    }

    /**
     * @param \Google_Service_Datastore_Key|\Google_Service_Datastore_KeyPathElement|Path\Builder|Builder $parentKey_or_parentPath
     * @return $this
     * @throws ParameterException
     */
    public function withParent($parentKey_or_parentPath)
    {
        if ($parentKey_or_parentPath instanceof Builder)
            $this->parent = $parentKey_or_parentPath->getPath();
        else if ($parentKey_or_parentPath instanceof Path\Builder)
            $this->parent = $parentKey_or_parentPath->build();
        else if ($parentKey_or_parentPath instanceof \Google_Service_Datastore_Key)
            $this->parent = $parentKey_or_parentPath->getPath();
        else if ($parentKey_or_parentPath instanceof \Google_Service_Datastore_KeyPathElement)
            $this->parent = $parentKey_or_parentPath;
        else
            throw new ParameterException(__METHOD__, [\Google_Service_Datastore_Key::class, \Google_Service_Datastore_KeyPathElement::class, Path\Builder::class, Builder::class ]);

        return $this;
    }

    /**
     * @return \Google_Service_Datastore_Key
     * @throws MissingFieldsException
     */
    public function build()
    {
        $partitionId = new \Google_Service_Datastore_PartitionId;
        $partitionId->setDatasetId($this->datasetId);
        $partitionId->setNamespace($this->namespace);

        $key = new \Google_Service_Datastore_Key;
        $key->setPartitionId($partitionId);
        $key->setPath($this->getPath());

        return $key;
    }

    /**
     * @param \Google_Service_Datastore_Key $key
     * @return Builder
     */
    public static function fromKey($key)
    {
        $keyBuilder = new self;
        $keyBuilder->parent = $key->getPath();

        /** @var \Google_Service_Datastore_KeyPathElement $latestPath */
        $latestPath = array_pop($keyBuilder->parent);
        $keyBuilder->kind = $latestPath->getKind();
        $keyBuilder->id = $latestPath->getId();
        $keyBuilder->name = $latestPath->getName();

        return $keyBuilder;
    }
}
