<?php
class Chaplin_Config_Amqp
    extends Chaplin_Config_Abstract
{
    const CONFIG_TYPE = 'Json';

    protected function _getConfigType()
    {
        return self::CONFIG_TYPE;
    }

    protected function _getConfigFile()
    {
        return realpath(APPLICATION_PATH.'/config/amqp.json');
    }
    
    public function getConfigArray()
    {
        return $this->_getValue($this->_zendConfig->exchanges, 'exchanges')->toArray();
    }
}
