<?php
class Chaplin_Model_Field_FieldId
    extends Chaplin_Model_Field_Abstract
{
    private $_mixedValue;
    
    public static function create($mixedValue)
    {
        $this->_mixedValue = $mixedValue;
    }
    
    public function getValue()
    {
        return $this->_mixedValue;
    }
    
    public function setValue($strValue)
    {
        if(!is_null($this->_mixedValue)) {
            throw new Chaplin_Model_Field_Exception_ReadOnly();
        }
        $this->_mixedChanges = $strValue;
        $this->_mixedValue = $strValue;
        return $this;
    }
    
    public function getChanges()
    {
        return $this->_mixedChanges;
    }
}   
