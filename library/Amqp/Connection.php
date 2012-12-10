<?php
class Amqp_Connection
{
    private $_amqpConnection;    
    
    public function __construct(Array $arrCredentials = array())
    {
        $this->_amqpConnection = new AMQPConnection($arrCredentials);
    }
    
    public function getConnection()
    {
        return $this->_amqpConnection;
    }
    
    public function getHost()
    {
        return $this->_amqpConnection->getHost();
    }
    
    public function setHost($strHost)
    {
        return $this->_amqpConnection->setHost($strHost);
    }
    
    public function getLogin($strLogin)
    {
        return $this->_amqpConnection->getLogin();
    }
        
    public function setLogin($strLogin)
    {
        return $this->_amqpConnection->setLogin($strLogin);
    }
    
    public function getPassword()
    {
        return $this->_amqpConnection->getPassword();
    }
    
    public function setPassword($strPassword)
    {
        return $this->_amqpConnection->setPassword($strPassword);
    }
    
    public function getPort()
    {
        return $this->_amqpConnection->getPort();
    }
    
    public function setPort($intPort)
    {
        return $this->_amqpConnection->setPort($intPort);
    }
    
    public function getTimeout()
    {
        return $this->_amqpConnection->getTimeout();
    }
    
    public function setTimeout($intTimeout)
    {
        return $this->_amqpConnection_>setTimeout($intTimeout);
    }
    
    public function getVhost()
    {
        return $this->_amqpConnection->getVhost();
    }
    
    public function setVhost($strVhost)
    {
        return $this->_amqpConnection->setVhost($strVhost);
    }
    
    public function isConnected()
    {
        return $this->_amqpConnection->isConnected();
    }
    
    public function connect()
    {
        return $this->_amqpConnection->connect();
    }
    
    public function disconnect()
    {
        return $this->_amqpConnection->disconnect();
    }
    
    public function reconnect()
    {
        return $this->_amqpConnection->reconnect();
    }
}
