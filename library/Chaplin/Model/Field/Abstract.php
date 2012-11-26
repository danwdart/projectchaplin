<?php
abstract class Chaplin_Model_Field_Abstract
{
    protected $_mixedChanges;

    public function __construct()
    {
        // use when initiating
    }
    
    public function bIsDirty()
    {
        return !empty($this->getChanges());
    }
    
    abstract public function setValue($value);
    
    abstract public function getChanges();
}
