<?php
class Chaplin_Config_Servers
    extends Chaplin_Config_Abstract
{
    const CONFIG_TYPE = 'Ini';

    protected function _getConfigType()
    {
        return self::CONFIG_TYPE;
    }

    protected function _getConfigFile()
    {
        return realpath(APPLICATION_PATH.'/../config/servers.ini');
    }
    
    public function getRedisSettings()
    {
        return $this->_zendConfig->phpredis;
    }

    public function getAmqpSettings()
    {
        return $this->_getValue(
            $this->_zendConfig->amqp,
            'amqp'
        )->toArray();   
    }

    public function getConfigConnectionRead()
    {
        return $this->_getValue(
            $this->_zendConfig->amqp->servers->read,
            'amqp.servers.read'
        )->toArray();
    }
    
    public function getConfigConnectionWrite()
    {
        return $this->_getValue(
            $this->_zendConfig->amqp->servers->write,
            'amqp.servers.write'
        )->toArray();
    }

    public function getSmtpSettings()
    {
        return $this->_getValue(
            $this->_zendConfig->smtp,
            'smtp'
        )->toArray();      
    }

    public function getSqlSettings()
    {
        return $this->_getValue(
            $this->_zendConfig->sql,
            'sql'
        );   
    }

    public function getMongoSettings()
    {
        return $this->_getValue(
            $this->_zendConfig->sql,
            'sql'
        );
    }
}
