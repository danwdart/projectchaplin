<?php
class Chaplin_Model_Exception_FieldDoesNotExist extends Zend_Exception
{
    const MESSAGE = 'The field called from class (%s) named (%s) does not exist.';

    public function __construct($strClass, $strField)
    {
        parent::__construct(sprintf(self::MESSAGE, $strClass, $strField));
    }
}
