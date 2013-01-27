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
