<?php
class Chaplin_Config_Gateways
    extends Chaplin_Config_Abstract
{
    const CONFIG_TYPE = 'Ini';

    protected function _getConfigType()
    {
        return self::CONFIG_TYPE;
    }

    protected function _getConfigFile()
    {
        return realpath(APPLICATION_PATH.'/config/gateways.ini');
    }
    
    public function getDaoType($strGatewayType)
    {
        return $this->_zendConfig->gateways->$strGatewayType->type;
    }
}
