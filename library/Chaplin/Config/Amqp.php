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
    
    public function getConfigArray()
    {
        return $this->_getValue($this->_zendConfig->exchanges, 'exchanges')->toArray();
    }
    
    public function getConfigConnectionRead()
    {
        return $this->_getValue(
            $this->_zendConfig->server->read,
            'server.read'
        )->toArray();
    }
    
    public function getConfigConnectionWrite()
    {
        return $this->_getValue(
            $this->_zendConfig->server->write,
            'server.write'
        )->toArray();
    }
}
