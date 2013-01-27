<?php
/**
 * This file is part of Project Chaplin.
 *
 * Project Chaplin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Project Chaplin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Project Chaplin. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    Project Chaplin
 * @author     Dan Dart
 * @copyright  2012-2013 Project Chaplin
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version    git
 * @link       https://github.com/dandart/projectchaplin
**/
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
