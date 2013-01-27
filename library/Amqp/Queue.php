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
class Amqp_Queue
{
    private $_amqpChannel;
    private $_amqpQueue;

    public function __construct(AMQP_Channel $amqpChannel)
    {
        $this->_amqpChannel = $amqpChannel;
        $this->_amqpQueue = new AMQPQueue($amqpChannel->getAMQPChannel());
    }
    
    public function ack($strDeliveryTag, $intFlags = AMQP_NOPARAM)
    {
        return $this->_amqpQueue->ack($strDeliveryTag, $intFlags);
    }
        
    public function bind($strExchangeName, $strRoutingKey)
    {
        return $this->_amqpQueue->bind($strExchangeName, $strRoutingKey);
    }

    public function cancel($strConsumerTag = "")
    {
        return $this->_amqpQueue->cancel($strConsumerTag);
    }

    public function consume(callable $callback, $intFlags = AMQP_NOPARAM)
    {
        $callback2 = function(AMQPEnvelope $amqpEnvelope)  use ($callback) {
            $callback(new Amqp_Envelope($amqpEnvelope));
        };
        return $this->_amqpQueue->consume($callback2, $intFlags);
    }
    
    public function declareQueue()
    {
        return $this->_amqpQueue->declare();
    }
    
    public function delete()
    {
        return $this->_amqpQueue->delete();
    }
    
    public function get($intFlags)
    {
        return $this->_amqpQueue->get($intFlags);
    }
    
    public function getArgument($key)
    {
        return $this->_amqpQueue->getArgument($strKey);
    }
    
    public function setArgument($strKey, $mixedVvalue)
    {
        return $this->_amqpQueue->setArgument($strKey, $mixedValue);
    }
    
    public function getArguments()
    {
        return $this->_amqpQueue->getArguments();
    }
    
    public function setArguments(Array $arrArguments)
    {
        return $this->_amqpQueue->setArguments($arrArguments);
    }    
    
    public function getFlags()
    {
        return $this->_amqpQueue->getFlags();
    }
    
    public function setFlags($intFlags)
    {
        return $this->_amqpQueue->setFlags($intFlags);
    }
    
    public function getName()
    {
        return $this->_amqpQueue->getName();
    }
    
    public function setName($strQueueName)
    {
        return $this->_amqpQueue->setName($strQueueName);
    }
    
    public function nack($strDeliveryTag, $intFlags = AMQP_NOPARAM)
    {
        return $this->_amqpQueue->nack($strDeliveryTag, $intFlags);
    }
    
    public function purge()
    {
        return $this->_amqpQueue->purge();
    }
        
    public function unbind($strExchangeName, $strRoutingKey)
    {
        return $this->_amqpQueue->unbind($strExchangeName, $strRoutingKey);
    }
}
