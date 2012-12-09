<?php
class Chaplin_Config_Amqp
    extends Chaplin_Config_Abstract
{
    const CONFIG_TYPE = 'Xml';

    protected function _getConfigType()
    {
        return self::CONFIG_TYPE;
    }

    protected function _getConfigFile()
    {
        return realpath(APPLICATION_PATH.'/../config/amqp.xml');
    }
}
