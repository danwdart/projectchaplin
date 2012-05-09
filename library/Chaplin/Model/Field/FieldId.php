<?php
class Chaplin_Model_Field_FieldId extends Chaplin_Model_Field_Abstract
{
    private $_mixedValue = null;

    public function isDefault()
    {
        return is_null($this->_mixedValue);
    }

    public function setValue($mixedValue)
    {
        if(!$this->isDefault()) {
            throw new _Chaplin_Model_Abstract_Exception_CannotResetId();
        } 
        $this->_isDirty = true;
        $this->_mixedValue = $mixedValue;
    }

    public function getValue()
    {
        return $this->_mixedValue;
    }
}

