<?php
abstract class Chaplin_Config_Abstract
{
    protected static $_instance;
    
    protected $_zendConfig;
    
    public static function getInstance()
    {
        $strClass = get_called_class();
        if(!is_null($strClass::$_instance)) {
           return $strClass::$_instance;
        }
        
        $instance = new $strClass();
        $strClass::$_instance = $instance;
        return $instance;
    }
     
    public static function inject(Chaplin_Config_Abstract $mockInstance)
    {
        $strClass = get_called_class();
        $strClass::$_instance = $mockInstance;
        return $mockInstance;
    }

    public static function reset()
    {
        $strClass = get_called_class();
        $strClass::$_instance = null;
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
