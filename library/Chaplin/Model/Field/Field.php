<?php
class Chaplin_Model_Field_Field
    extends Chaplin_Model_Field_Abstract
{
    private $_mixedValue;
    
    public function setFromData($mixedValue)
    {
        $this->_mixedValue = $mixedValue;
    }       
    
    public function setValue($mixedValue)
    {
        $this->_mixedValue = $mixedValue;
        $this->_bIsDirty = true;
    }
        
    public function getValue($mixedDefault)
    {
        return (is_null($this->_mixedValue))?
            $mixedDefault:
            $this->_mixedValue;
    }
}  
