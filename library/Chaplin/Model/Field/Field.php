<?php
class Chaplin_Model_Field_Field
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
        $this->_mixedValue = $strValue;
        $this->_mixedChanges = $strValue;
        return $this;
    }
    
    public function getChanges()
    {
        return $this->_mixedChanges;
    }
}   
