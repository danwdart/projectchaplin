<?php
abstract class Chaplin_Config_Abstract
{
    private static $_arrInstances;
    
    protected $_zendConfig;
    
    public static function getInstance()
    {
        $strClass = get_called_class();
        if(isset(self::$_arrInstances[$strClass])) {
           return self::$_arrInstances[$strClass];
        }
        
        $instance = new $strClass();
        self::$_arrInstances[$strClass] = $instance;
        return $instance;
    }
     
    public static function inject(Chaplin_Config_Abstract $mockInstance)
    {
        $strClass = get_called_class();
        self::$_arrInstances[$strClass] = $mockInstance;
        return $mockInstance;
    }

    public static function reset()
    {
        self::$_arrInstances = array();
    }
    
    private function __construct()
    {
        $strConfigFile = $this->_getConfigFile();
        if(!file_exists($strConfigFile)) {
            throw new Exception($strConfigFile);
        }

        $strConfigClass = 'Zend_Config_'.$this->_getConfigType();
        
        if(!class_exists($strConfigClass)) {
            throw new Exception('Config class '.$strConfigClass.' does not exist');
        }

        $this->_zendConfig = new $strConfigClass(
            $strConfigFile,
            APPLICATION_ENV
        );
    }
    
    abstract protected function _getConfigFile();
    
    abstract protected function _getConfigType();

    protected function _getValue($strValue, $strKey)
    {
        if(is_null($strValue)) {
            throw new Exception(
                'Nonexistent key: '.$strKey.' on '.APPLICATION_ENV
            );
        }
        
        return $strValue;
    }

    protected function _getOptionalValue($strValue, $mixedDefault)
    {
        if(is_null($strValue)) {
            return $mixedDefault;
        }
        
        return $strValue;
    }
}
