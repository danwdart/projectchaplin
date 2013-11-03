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
class Amqp_Exchange
{
    const TYPE_DIRECT = AMQP_EX_TYPE_DIRECT;
    const TYPE_FANOUT = AMQP_EX_TYPE_FANOUT;
    const TYPE_TOPIC = AMQP_EX_TYPE_TOPIC;
//s    const TYPE_HEADER = AMQP_EX_TYPE_HEADER;

    private $_amqpChannel;
    private $_amqpExchange;

    public function __construct(Amqp_Channel $amqpChannel)
    {
        $this->_amqpChannel = $amqpChannel;
        $this->_amqpExchange = new AMQPExchange($amqpChannel->getAMQPChannel());
    }
    
    public function bind($strDestExchangeName, $strSourceExchangeName, $strRoutingKey)
    {
        return $this->_amqpExchange->bind($strDestExchangeName, $strSourceExchangeName, $strRoutingKey);
    }
        
    public function delete($intFlags)
    {
        return $this->_amqpExchange->delete($intFlags);
    }
    
    public function getArgument($strKey)
    {
        return $this->_amqpExchange->getArgument($strKey);
    }
    
    public function setArgument($strKey, $mixedValue)
    {
        return $this->_amqpExchange->setArgument($strKey, $mixedValue);
    }
    
    public function getArguments()
    {
        return $this->_amqpExchange->getArguments();
    }
    
    public function setArguments(Array $arrArguments)
    {
        return $this->_amqpExchange->setArguments($arrArguments);
    }    
    
    public function getFlags()
    {
        return $this->_amqpExchange->getFlags();
    }
    
    public function setFlags($intFlags)
    {
        return $this->_amqpExchange->setFlags($intFlags);
    }
    
    public function getName()
    {
        return $this->_amqpExchange->getName();
    }
    
    public function setName($strName)
    {
        return $this->_amqpExchange->setName($strName);
    }
    
    public function getType()
    {
        return $this->_amqpExchange->getType();
    }
    
    public function setType($strType)
    {
        return $this->_amqpExchange->setType($strType);
    }
    
    public function publish($strMessage, $strRoutingKey, $intFlags = null, Array $arrAttributes = array()) //flags
    {
        return $this->_amqpExchange->publish($strMessage, $strRoutingKey, $intFlags, $arrAttributes);
    }
}
