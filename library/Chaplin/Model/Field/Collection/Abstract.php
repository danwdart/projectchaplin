<?php
/**
 * This is a collection of any type of field.
**/
abstract class Chaplin_Model_Field_Collection_Abstract extends Chaplin_Model_Field_Abstract implements SeekableIterator, Serializable, Countable
{
    protected $_arrFields = array();
    protected $_intOffset = 0;

    public function next()
    {
        ++$this->_intOffset;
    }

    public function rewind()
    {
        $this->_intOffset = 0;
    }
    
    public function count()
    {
        return count($this->_arrFields);
    }

    public function isEmpty()
    {
        return 0 == count($this->_arrFields);
    }
}
