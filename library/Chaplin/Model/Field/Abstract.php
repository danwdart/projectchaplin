<?php
abstract class Chaplin_Model_Field_Abstract
{
    protected $_bIsDirty = false;
    
    public function bIsDirty()
    {
        return $this->_bIsDirty;
    }
    
    abstract public function getValue($mixedDefault);
}
