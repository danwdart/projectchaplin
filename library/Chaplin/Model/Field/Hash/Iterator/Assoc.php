<?php
class Chaplin_Model_Field_Hash_Iterator_Assoc
    extends Chaplin_Model_Field_Hash_Iterator_Abstract
{    
    private $_intOffset;

    public function setValue($value)
    {
        $fields = $this->getFields();
        $strType = $this->_strType;
        foreach($value as $strKey => $arrValue) {
            $field = new $strType();
            $field->setValue($arrValue);
            $fields[$strKey] = $field;
        }
        return $this;
    }

    public function getChanges()
    {
        $arrOut = array();
        foreach($this->_arrChanges as $strKey => $hash) {
            $arrOut[$strKey] = $hash->getValue();
        }
        return $arrOut;
    }

    public function current()
    {
        
    }
    
    public function current()
    {
    }
    
    public function key()
    {
    }
    
    public function next()
    {
    }
    
    public function rewind()
    {
    }
    
    public function valid()
    {
    }
}
