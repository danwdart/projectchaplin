<?php
class Chaplin_Gateway
{
    private static $_instance;
    
    private function __clone()
    {
    }

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if(is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function inject(Chaplin_Gateway $gateway)
    {
        self::$_instance = $gateway;
    }

    public function getUser()
    {
        return new Chaplin_Gateway_User(new Chaplin_Dao_Mongo_User());
    }
    
    public function getVideo()
    {
        return new Chaplin_Gateway_Video(new Chaplin_Dao_Mongo_Video());
    }
    
    public function getNode()
    {
        return new Chaplin_Gateway_Node(new Chaplin_Dao_Mongo_Node());
    }
}
