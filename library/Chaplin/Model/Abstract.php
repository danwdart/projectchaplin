<?php
abstract class Chaplin_Model_Abstract
{
    const FIELD_ID = '_id';    
    
    // Implement this
    protected static $_arrFields = array();

    // Each model has a collection of fields
    private $_collFields;

    protected function __construct()
    {
        $this->_collFields = new Chaplin_Model_Field_Collection_Assoc(static::$_arrFields);
    }

    private function _getFieldObject($strField)
    {
        try {
            return $this->_collFields->seek($strField);
        } catch(OutOfBoundsException $e) {
            throw new Chaplin_Model_Exception_FieldDoesNotExist(get_class($this), $strField);
        }
    }

    protected function _getCollFields()
    {
        return $this->_collFields;
    }

    protected function _addChildToCollection(Chaplin_Model_Abstract_Child $modelChild)
    {
        $strClass = get_class($modelChild);
        $strFieldName = $strClass::getParentFieldName(); // @TODO protect this
        $collection = $this->_getField($strFieldName, array());
        $collection->add($modelChild);
    }

    protected function _getField($strField, $mixedDefault)
    {
        return $this->_getFieldObject($strField)->getValue($mixedDefault);
    }

    protected function _setField($strField, $mixedDefault)
    {
        return $this->_getFieldObject($strField)->setValue($mixedDefault);
    }

    abstract public function save();
}
