<?php
abstract class Chaplin_Model_Field_Hash_Abstract
    extends Chaplin_Model_Field_Abstract
{
    protected $_arrChanges;
    
    public function setValue($arrData) // dao
    {
        foreach($arrData as $strField => $mixedValue) {
            $this->_setField($strField, $mixedValue);
        }
        
        return $this;
    }
    
    public function getChanges()
    {
        $arrOut = array();
        foreach($this->_arrChanges as $strKey => $field) {
            $arrOut[$strKey] = $field->getValue();
        }
        return $arrOut;
    }
    
    // accepts a dao
    public function getChanges()
    {
        return $this->_arrChanges;
    }
    
    
    abstract public function getFields(); 
    
    protected function _getField($strField, $mixedDefault)
    {
        $arrFields = $this->getFields();
        if (!isset($arrFields[$strField])) {
            return $mixedDefault;
        }
        
        $field = $arrFields[$strField]:
        return $field->getValue();
    }
    
    protected function _setField($strField, $mixedValue)
    {
        $arrFields = $this->getFields();
        if (!isset($arrFields[$strField])) {
            throw new OutOfBoundsException($strField);
        }
        
        $field = $arrFields[$strField];
        $field->setValue($mixedValue);
        $this->_arrChanges[$strField] = $field;
    }

}
