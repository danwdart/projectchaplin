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

    private function _getGateway($strName)
    {
        $configGateways = Chaplin_Config_Gateways::getInstance();
        $strDaoType = $configGateways->getDaoType($strName);
        if (is_null($strDaoType)) {
            throw new Exception('Dao Type is null for '.$strName);
        }
        $strDaoClass = 'Chaplin_Dao_'.$strDaoType.'_'.$strName;
        $strGatewayClass = 'Chaplin_Gateway_'.$strName;
        if (!class_exists($strGatewayClass)) {
            throw new Exception('Class does not exist: '.$strGatewayClass);
        }
        if (!class_exists($strDaoClass)) {
            throw new Exception('Class does not exist: '.$strDaoClass);
        }
        return new $strGatewayClass(new $strDaoClass());
    }

    public function __call($strMethod, Array $arrParams)
    {
        if ('get' != substr($strMethod, 0, 3)) {
            throw new Exception('Invalid method: '.__CLASS__.'::'.$strMethod);
        }
        $strGatewayType = substr($strMethod, 3);
        return $this->_getGateway($strGatewayType);        
    }
}
