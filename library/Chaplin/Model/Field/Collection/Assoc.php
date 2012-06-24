<?php
/**
 * This is a collection of any type of field.
**/
class Chaplin_Model_Field_Collection_Assoc extends Chaplin_Model_Field_Collection_Abstract
{
    private $_arrKeys = array();

    public function __construct(Array $arrFields = array())
    {
	$arrObjFields = array();
	// Not sure where this should go but...
	foreach($arrFields as $strKey => $field) {
	    $arrObjFields[$strKey] = new $field;
	}
        $this->_arrFields = $arrObjFields;
        $this->_arrKeys = array_keys($arrFields);
    }
    
    public function getValue()
    {
        return $this;
    }

    public function seek($strKey)
    {
        if(!isset($this->_arrFields[$strKey])) {
            throw new OutOfBoundsException($strKey. ' is not a valid key');
        }

        return $this->_arrFields[$strKey];
    }        

    public function current()
    {
        if(!$this->valid()) {
            throw new OutOfBoundsException($this->_intOffset. ' is not a valid offset');
        }    
        return $this->_arrFields[$this->_arrKeys[$this->_intOffset]];
    }

    public function key()
    {
        return $this->_arrKeys[$this->_intOffset];
    }

    public function valid()
    {
        return isset($this->_arrKeys[$this->_intOffset]);
    }
    
    public function serialize()
    {
        return serialize($this->_arrFields);
    }

    public function unserialize($strSerialized)
    {
        $this->_arrFields = unserialize($strSerialized);
        $this->_arrKeys = array_keys($this->_arrFields);
    }
}
