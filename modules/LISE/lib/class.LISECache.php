<?php

class LISECache extends ArrayObject {
    #---------------------
    # Variables
    #---------------------	

    private $_identifiers;

    #---------------------
    # Magic methods
    #---------------------	

    public function __construct($identifiers) {
        $this->_identifiers = $identifiers;
    }

    public function __toString() {
        return "Array";
    }

    #---------------------
    # Array Object methods
    #---------------------		

    public function offsetSet($key, $value) {
        if (!is_object($value))
            throw new \LISE\Exception(__METHOD__ . ": Value type must be object!");

        $key = reset($this->_identifiers);

        parent::offsetSet($value->$key, $value);
    }

    public function offsetExists($offset) {
        if (parent::offsetExists($offset))
            return true;

        return FALSE;
    }

    public function offsetGet($offset) {
        if (parent::offsetExists($offset))
            return parent::offsetGet($offset);

        return null;
    }

    #---------------------
    # Class methods
    #---------------------		

    public function GetCachedByIdentifier($identifier, $value) {
        if (!in_array($identifier, $this->_identifiers))
            throw new \LISE\Exception(__METHOD__ . ": Illegal identifier: $identifier!");

        foreach ($this as $cached) {

            if ($cached->$identifier == $value) {

                return $cached;
            }
        }

        return null;
    }

}

// end of class