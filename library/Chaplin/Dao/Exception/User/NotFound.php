<?php
class Chaplin_Dao_Exception_User_NotFound extends Zend_Exception
{
    const MESSAGE = 'User with specified credentials was not found.';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
