<?php

namespace DatastoreHelper\Key\Path;

use DatastoreHelper\Exception\MissingFieldsException;
use DatastoreHelper\Interfaces\IBuilder;

class Builder implements IBuilder
{
    /**
     * @var string $kind
     */
    protected $kind = null;

    /**
     * @var string $id
     */
    protected $id = null;

    /**
     * @var string $name
     */
    protected $name = null;

    /**
     * @return string
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * @param string $kind
     * @return $this
     */
    public function withKind($kind)
    {
        $this->kind = $kind;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function withId($id)
    {
        if ($this->name !== null)
            trigger_error('Keys can only have one of either id or name.', E_USER_WARNING);
        else
            $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function withName($name)
    {
        if ($this->id !== null)
            trigger_error('Keys can only have one of either id or name.', E_USER_WARNING);
        else
            $this->name = $name;

        return $this;
    }

    public function build()
    {
        $missing = [];
        if ($this->kind === null) $missing[] = '$kind';
        if ($this->id === null && $this->name === null) $missing[] = '$id or $name';

        if (!empty($missing))
            throw new MissingFieldsException(self::class, $missing);

        $path = new \Google_Service_Datastore_KeyPathElement;
        $path->setKind($this->kind);

        if ($this->id !== null)
            $path->setId($this->id);
        else if ($this->name !== null)
            $path->setName($this->name);

        return $path;
    }
}
