<?php
class Chaplin_Model_Field_FieldId
    extends Chaplin_Model_Field_Abstract
{
    private $_mixedValue;
    
    public function setFromData($mixedValue)
    {
        $this->_mixedValue = $mixedValue;
    }
    
    public function setValue($mixedValue)
    {
        if (!is_null($this->_mixedValue)) {
            throw new Exception('id fields are read-only');
        }
        $this->_mixedValue = $mixedValue;
        $this->_bIsDirty = true;
    }
    
    public function getValue($mixedDefault)
    {
        return $this->_mixedValue;
    }
}  
