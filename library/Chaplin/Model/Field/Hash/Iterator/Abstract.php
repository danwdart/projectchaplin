<?php
abstract class Chaplin_Model_Field_Hash_Iterator_Abstract
    extends Chaplin_Model_Field_Hash_Abstract
    extends Countable, Iterator //ArrayAccess
{
    protected $_strType;

    public function __construct($strType)
    {
        $this->_strType = $strType;
    }
}
