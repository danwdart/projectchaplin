<?php
abstract class Chaplin_Message_Abstract
    implements JsonSerializable
{
    const FIELD_MESSAGE_CLASS = 'MessageClass';

    private $_arrData = array();
    
    protected function __construct()
    {
        $this->_setField(self::FIELD_MESSAGE_CLASS, get_class($this));
    }
    
    protected function _setField($strField, $strValue)
    {
        $this->_arrData[$strField] = $strValue;
        return $this;
    }
    
    protected function _getField($strField, $mixedDefault)
    {
        return (isset($this->_arrData[$strField]))?
            $this->_arrData[$strField]:
            $mixedDefault;
    }

    public static function createFromDao(Amqp_Envelope $message)
    {   
        $arrData = Zend_Json::decode($message->getBody());
        if(!isset($arrData[self::FIELD_MESSAGE_CLASS])) {
            throw new Exception(
                'Unknown Message Class for message on '.
                $message->getRoutingKey()
            );
        }
        
        $strMessageClass = $arrData[self::FIELD_MESSAGE_CLASS];
        
        $message = new $strMessageClass();
        $message->_arrData = $arrData;
        return $message;        
    }
    
    public function jsonSerialize()
    {
        return $this->_arrData;
    }
    
    public function send()
    {
        Chaplin_Service::getInstance()
            ->getExchange($this->getExchangeName())
            ->publishMessage($this, $this->getRoutingKey());
    }    

    abstract public function getRoutingKey();

    abstract public function getExchangeName();
}  
