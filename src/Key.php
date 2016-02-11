<?php

namespace DatastoreHelper;

class Key
{
    /**
     * @param \Google_Service_Datastore_Key $key
     * @return \Google_Service_Datastore_KeyPathElement
     */
    public static function getLastPath($key)
    {
        $path = $key->getPath();
        return $path[count($path) - 1];
    }
}
