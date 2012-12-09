<?php
class Amqp_Exchange
{
    const TYPE_DIRECT = AMQP_DIRECT;
    const TYPE_FANOUT = AMQP_FANOUT;
    const TYPE_TOPIC = AMQP_TOPIC;
    const TYPE_HEADER = AMQP_HEADER;

    private $_amqpChannel;
    private $_amqpExchange;

    public function __construct(Amqp_Channel $amqpChannel)
    {
        $this->_amqpChannel = $amqpChannel;
        $this->_amqpExchange = new AMQPExchange($amqpChannel);
    }
    
    public function bind($strDestExchangeName, $strSourceExchangeName, $strRoutingKey)
    {
        return $this->_amqpExchange->bind($strDestExchangeName, $strSourceExchangeName, $strRoutingKey);
    }
    
    public function declare()
    {
        return $this->_amqpExchange->declare();
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
        $this_>_amqpExchange->setArguments($arrArguments);
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
