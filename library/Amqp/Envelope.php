<?php
class Amqp_Envelope
{
    private $_amqpEnvelope;

    public function __construct(AMQPEnvelope $amqpEnvelope)
    {
        $this->_amqpEnvelope = $amqpEnvelope;
    }

    public function getAppId()
    {
        return $this->_amqpEnvelope->getAppId();
    }

    public function getBody()
    {
        return $this->_amqpEnvelope->getBody();
    }
    
    public function getContentEncoding()
    {
        return $this->_amqpEnvelope->getContentEncoding();
    }
    
    public function getContentType()
    {
        return $this->_amqpEnvelope->getContentType();
    }
    
    public function getCorrelationId()
    {
        return $this->_amqpEnvelope->getCorrelationId();
    }
    
    public function getDeliveryTag()
    {
        return $this->_amqpEnvelope->getDeliveryTag();
    }
    
    public function getExchange()
    {
        return $this->_amqpEnvelope->getExchange();
    }
    
    public function getExpiration()
    {
        return $this->_amqpEnvelope->getExpiration();
    }
    
    public function getHeader($strHeaderKey)
    {
        return $this->_amqpEnvelope->getHeader($strHeaderKey);
    }
    
    public function getHeaders()
    {
        return $this->_amqpEnvelope->getHeaders();
    }
    
    public function getMessageId()
    {
        return $this->_amqpEnvelope->getMessageId();
    }
    
    public function getPriority()
    {
        return $this->_amqpEnvelope->getPriority();
    }
    
    public function getReplyTo()
    {
        return $this->_amqpEnvelope->getReplyTo();
    }
    
    public function getRoutingKey()
    {  
        return $this->_amqpEnvelope->getRoutingKey();
    }
    
    public function getTimeStamp()
    {
        return $this->_amqpEnvelope->getTimestamp();
    }
    
    public function getType()
    {
        return $this->_amqpEnvelope->getType();
    }
    
    public function getUserId()
    {
        return $this->_amqpEnvelope->getUserId();
    }
    
    public function isRedelivery()
    {
        return $this->_amqpEnvelope->isRedelivery();
    }
}
