<?php
class Chaplin_Config_Chaplin
    extends Chaplin_Config_Abstract
{
    const CONFIG_TYPE = 'Ini';

    protected function _getConfigType()
    {
        return self::CONFIG_TYPE;
    }

    protected function _getConfigFile()
    {
        return realpath(APPLICATION_PATH.'/config/chaplin.ini');
    }
    
    public function getLocale()
    {
        return $this->_getValue(
            $this->_zendConfig->locale,
            'locale'
        );
    }
}
