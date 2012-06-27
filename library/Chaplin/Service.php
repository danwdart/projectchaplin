<?php
class Chaplin_Service
{
    public static function inject(Chaplin_Service $service)
    {
        self::$_instance = $service;
    }
 
    private static $_instance;
    public static function getInstance()
    {
        if (is_null(self::$_instance))
            self::$_instance   = new Chaplin_Service();
        return self::$_instance;
    }
    private function __construct() {}
    private function __clone() {}   

    private $_zendCache;
    private function getCache()
    {
        if (is_null($this->_zendCache)) {
            //@TODO - probably put this in a config file
            $frontendOptions    = array('lifetime' => NULL, 'automatic_serialization' => true);
            if (Zend_Registry::isRegistered(Chaplin_Dao_PhpRedis_Abstract::DEFAULT_REGISTRY_KEY)){
                $backendOptions = array(
                    'phpredis' => Zend_Registry::get(Chaplin_Dao_PhpRedis_Abstract::DEFAULT_REGISTRY_KEY)
                    );
                $backendType = new Chaplin_Cache_Backend_PhpRedis($backendOptions);
            } else {
                $backendOptions     = array('cache_dir' => '/zendCache/');
                $backendType        = 'File';
            }

            $this->_zendCache   = Zend_Cache::factory('Core'
                ,$backendType
                ,$frontendOptions
                ,$backendOptions);
        }
        return $this->_zendCache;
    }
    
    public function setCache(Zend_Cache $zendCache)
    {
        $this->_zendCache   = $zendCache;
    }

    public function getHttpClient()
    {
        $objClient = new Chaplin_Http_Client();
        $objCache  = new Chaplin_Cache_Http_Client($objClient, $this->getCache());
        return new Chaplin_Service_Http_Client($objCache);
    }
    
    public function getFFMpeg()
    {
        return new Chaplin_Service_FFMpeg_API();
    }
    
    public function getAVConv()
    {
        return new Chaplin_Service_AVConv_API();
    }
}
