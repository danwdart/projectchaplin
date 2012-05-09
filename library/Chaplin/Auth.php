<?php
class Chaplin_Auth extends Zend_Auth
{
    public static function getInstance()
    {    
        if (null === self::$_instance) {
            self::$_instance = new self();
        }    

        return self::$_instance;
    }

    public static function inject(Zend_Auth $auth)
    {
        self::$_instance = $auth;
    }
}
