<?php
/**
 * This is a collection of any type of field.
**/
class Chaplin_Model_Field_Collection_Index extends Chaplin_Model_Field_Collection_Abstract
{
    private $_arrFields = array();
    private $_intOffset = 0;

    /** An Index child must not be created with any fields **/
    public function __construct()
    {
        $this->_arrFields = array();
    }

    public function seek($intOffset)
    {
        if(!isset($this->_arrFields[$intOffset])) {
            throw new OutOfBoundsException($intOffset. ' is not a valid key');
        }

        return $this->_arrFields[$strKey];
    }        

    public function current()
    {
        if($this->valid()) {
            throw new OutOfBoundsException($this->_intOffset. ' is not a valid offset');
        }    
        return $this->_arrFields[$this->_intOffset];
    }

    public function key()
    {
        return $this->_intOffset;
    }

    public function next()
    {
        $this->_intOffset++;
    }

    public function add(Chaplin_Model_Abstract_Child $child)
    {
        $this->_arrFields[] = $child;
        $this->_arrKeys[] = $child->getCId();
    }

    public function rewind()
    {
        $this->_intOffset = 0;
    }

    public function valid()
    {
        return isset($this->_arrFields[$this->_intOffset]);
    }
    
    public function count()
    {
        return count($this->_arrFields);
    }

    public function serialize()
    {
        return serialize($this->_arrFields);
    }

    public function unserialize($strSerialized)
    {
        $this->_arrFields = unserialize($strSerialized);
    }
}
