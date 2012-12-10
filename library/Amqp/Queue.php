<?php
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
