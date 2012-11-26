<?php
class Chaplin_Model_Field_Exception_ReadOnly
    extends Exception
{
    const MESSAGE = 'Field is read only.';
    
    public function __construct()
    {
        parent::__construct(sprintf(self::MESSAGE));
    }
}
