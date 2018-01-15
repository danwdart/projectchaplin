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
 * @package   ProjectChaplin
 * @author    Dan Dart <chaplin@dandart.co.uk>
 * @copyright 2012-2018 Project Chaplin
 * @license   http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version   GIT: $Id$
 * @link      https://github.com/danwdart/projectchaplin
**/
class Chaplin_Dao_Amqp_Exchange
    implements Chaplin_Dao_Interface
{
    const CONFIG_TYPE = 'Type';
    const CONFIG_FLAGS = 'Flags';
    const CONFIG_QUEUES = 'Queues';
    const CONFIG_QUEUE_KEYS = 'Keys';

    private $_strExchangeName;
    private $_strExchangeType;
    private $_arrExchange;

    private static $_amqpConnectionRead;
    private static $_amqpConnectionWrite;

    public function __construct($strExchangeName)
    {
        if (is_null($strExchangeName)) {
            throw new Exception('Cannot create with blank exchange name');
        }

        $this->_strExchangeName = $strExchangeName;

        $arrExchanges = Chaplin_Config_Amqp::getInstance()
            ->getConfigArray();

        if (!isset($arrExchanges[$strExchangeName])
            || !is_array($arrExchanges[$strExchangeName])
        ) {
            throw new Exception($strExchangeName.' exchange not found');
        }

        $this->_arrExchange = $arrExchanges[$strExchangeName];

        if (!isset($this->_arrExchange[self::CONFIG_TYPE])) {
            throw new Canddi_Dao_Exception_Message_ExchangeTypeEmpty();
        }
    }

    private static function _getReadConnection()
    {
        if (is_null(self::$_amqpConnectionRead)) {
            self::$_amqpConnectionRead = new Amqp\Connection(
                [
                    "host" => getenv("AMQP_HOST"),
                    "port" => getenv("AMQP_PORT"),
                    "username" => getenv("AMQP_USER"),
                    "password" => getenv("AMQP_PASSWORD"),
                    "vhost" => getenv("AMQP_VHOST")
                ]
            );
            if (!self::$_amqpConnectionRead->isConnected()) {
                self::$_amqpConnectionRead->connect();
            }
            if (!self::$_amqpConnectionRead->isConnected()) {
                throw new Exception('Connection exception');
            }
        }

        return self::$_amqpConnectionRead;
    }

    private static function _getWriteConnection()
    {
        if (is_null(self::$_amqpConnectionWrite)) {
            self::$_amqpConnectionWrite = new Amqp\Connection(
                [
                    "host" => getenv("AMQP_HOST"),
                    "port" => getenv("AMQP_PORT"),
                    "username" => getenv("AMQP_USER"),
                    "password" => getenv("AMQP_PASSWORD"),
                    "vhost" => getenv("AMQP_VHOST")
                ]
            );
            if (!self::$_amqpConnectionWrite->isConnected()) {
                self::$_amqpConnectionWrite->connect();
            }
            if (!self::$_amqpConnectionWrite->isConnected()) {
                throw new Exception('Connection exception');
            }
        }

        return self::$_amqpConnectionWrite;
    }

    private function _getReadExchange()
    {
        $amqpConnection = self::_getReadConnection();

        $arrFlags = (isset($this->_arrExchange[self::CONFIG_FLAGS]))?
            $this->_arrExchange[self::CONFIG_FLAGS]:
            array();

        $intFlags = Amqp\Flags::getFlags($arrFlags);
        $amqpChannel = new Amqp\Channel($amqpConnection);
        $exchange = new Amqp\Exchange($amqpChannel);
        $exchange->setName($this->_strExchangeName);
        $exchange->setType($this->_arrExchange[self::CONFIG_TYPE]);
        $exchange->setFlags($intFlags);
        $exchange->declareExchange();
        return $exchange;
    }

    private function _getWriteExchange()
    {
        $amqpConnection = self::_getWriteConnection();

        $arrFlags = (isset($this->_arrExchange[self::CONFIG_FLAGS]))?
            $this->_arrExchange[self::CONFIG_FLAGS]:
            array();

        $intFlags = Amqp\Flags::getFlags($arrFlags);
        $amqpChannel = new Amqp\Channel($amqpConnection);
        $exchange = new Amqp\Exchange($amqpChannel);
        $exchange->setName($this->_strExchangeName);
        $exchange->setType($this->_arrExchange[self::CONFIG_TYPE]);
        $exchange->setFlags($intFlags);
        $exchange->declareExchange();
        return $exchange;
    }

    /**
    *  Provides the queue listening functionality
     *
    *  @param: $queueName
    *  @param: $callback   - this is the function that will be called when a message is found
    **/
    public function listen($strQueue, Closure $callback)
    {
        if (!isset($this->_arrExchange[self::CONFIG_QUEUES])
            || !is_array($this->_arrExchange[self::CONFIG_QUEUES][$strQueue])
        ) {
            throw new Exception('Queue not found: '.$strQueue);
        }

        $arrQueue = $this->_arrExchange[self::CONFIG_QUEUES][$strQueue];

        if (!isset($arrQueue[self::CONFIG_QUEUE_KEYS])
            || !is_array($arrQueue[self::CONFIG_QUEUE_KEYS])
        ) {
            throw new Exception($strQueue.' has no keys');
        }

        $arrKeys = $arrQueue[self::CONFIG_QUEUE_KEYS];

        $arrFlags = (isset($arrQueue[self::CONFIG_FLAGS]))?
            $arrQueue[self::CONFIG_FLAGS]:
            array();

        $intFlags = Amqp\Flags::getFlags($arrFlags);
        $amqpConnection = $this->_getReadConnection();

        $amqpChannel = new Amqp\Channel($amqpConnection);
        $amqpQueue = new Amqp\Queue($amqpChannel);
        $amqpQueue->setName($strQueue);
        $amqpQueue->setFlags($intFlags);
        $amqpQueue->declareQueue();

        $localCallback  = function (
            Amqp\Envelope $amqpEnvelope
        ) use (
            $callback,
            $amqpQueue
        ) {
            $strBody = $amqpEnvelope->getBody();
            try {
                $arrData = Zend_Json::decode($strBody);
            } catch (Zend_Json_Exception $e) {
                echo 'Invalid Json: '.$strBody;
                ob_flush();
                flush();
                return;
            }
            $strClass = $amqpEnvelope->getType();
            if (!class_exists($strClass)) {
                echo 'Class does not exist: '.$strClass;
                ob_flush();
                flush();
                return;
            }

            if (!is_array($arrData)) {
                echo 'Not array: '.print_r($arrData, true);
                ob_flush();
                flush();
                return;
            }
            $model = $strClass::createFromData($this, $arrData);
            try {

                $callback($model);

                $amqpQueue->ack($amqpEnvelope->getDeliveryTag());
            } catch (Exception $e) {
                echo 'Caught Exception ('.get_class($e).'): '.$e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL;
            }
            ob_flush();
            flush();
        };

        // Make sure the exchange is created so there's something to listen on - DO NOT DELETE THIS
        $this->_getReadExchange();

        foreach($arrKeys as $strQueueKey) {
            $strRoutingKey = is_null($strQueueKey)?'#':$strQueueKey;
            $amqpQueue->bind($this->_strExchangeName, $strRoutingKey);
        }

        $amqpQueue->consume($localCallback, $intFlags);
    }

    /**
    *  Publishes the message
     *
    *  @param: $modelMessage
    **/
    public function publish(Chaplin_Model_Field_Hash $message, $strRoutingKey)
    {
        $this->_getWriteExchange()
            ->publish(
                Zend_Json::encode($message),
                $strRoutingKey,
                AMQP_NOPARAM,
                [
                    'content_type' => 'application/json',
                    'type' => get_class($message)
                ]
            );
    }

    public function save(Chaplin_Model_Field_Hash $model)
    {
        return $this->publish($model, $model->getRoutingKey());
    }
}
