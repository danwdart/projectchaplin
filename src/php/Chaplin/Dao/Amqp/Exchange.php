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

namespace Chaplin\Dao\Amqp;

use Chaplin\Dao\DaoInterface;
use Closure;
use Exception;
use Zend_Json;
use Zend_Json_Exception;
use Chaplin\Model\Field\Hash;
use Chaplin\Config\Amqp as ConfigAmqp;
use PhpAmqpLib\Connection\AMQPStreamConnection as Connection;
use PhpAmqpLib\Message\AMQPMessage as Message;

class Exchange implements DaoInterface
{
    const CONFIG_TYPE = 'Type';
    const CONFIG_FLAGS = 'Flags';
    const CONFIG_QUEUES = 'Queues';
    const CONFIG_QUEUE_KEYS = 'Keys';

    const FLAG_PASSIVE = "Passive";
    const FLAG_DURABLE = "Durable";
    const FLAG_EXCLUSIVE = "Exclusive";
    const FLAG_AUTODELETE = "AutoDelete";

    const TYPE_READ = "read";
    const TYPE_WRITE = "write";

    private $_strExchangeName;
    private $_strExchangeType;
    private $_arrExchange;

    private static $_amqpConnections = [
        self::TYPE_READ => null,
        self::TYPE_WRITE => null
    ];

    public function __construct($strExchangeName)
    {
        if (is_null($strExchangeName)) {
            throw new Exception('Cannot create with blank exchange name');
        }

        $this->_strExchangeName = $strExchangeName;

        $arrExchanges = ConfigAmqp::getInstance()
            ->getConfigArray();

        if (!isset($arrExchanges[$strExchangeName])
            || !is_array($arrExchanges[$strExchangeName])
        ) {
            throw new Exception($strExchangeName.' exchange not found');
        }

        $this->_arrExchange = $arrExchanges[$strExchangeName];

        if (!isset($this->_arrExchange[self::CONFIG_TYPE])) {
            throw new Exception("Exchange type empty.");
        }
    }

    private static function _getConnection(
        string $strType
    ): Connection {

        if (is_null(self::$_amqpConnections[$strType])) {
            self::$_amqpConnections[$strType] = new Connection(
                getenv("AMQP_HOST"),
                getenv("AMQP_PORT"),
                getenv("AMQP_USER"),
                getenv("AMQP_PASSWORD"),
                getenv("AMQP_VHOST")
            );
        }

        return self::$_amqpConnections[$strType];
    }

    private function _declareExchange(string $strType) : void
    {
        $connection = self::_getConnection($strType);

        $channel = $connection->channel();

        $arrFlags = (isset($this->_arrExchange[self::CONFIG_FLAGS]))?
            $this->_arrExchange[self::CONFIG_FLAGS]:
            array();

        $channel->exchange_declare(
            $this->_strExchangeName,
            'topic',
            $arrFlags[self::FLAG_PASSIVE] ?? false,
            $arrFlags[self::FLAG_DURABLE] ?? true,
            $arrFlags[self::FLAG_AUTODELETE] ?? false
        );
    }

    /**
    *  Provides the queue listening functionality
    *
    *  @param: $queueName
    *  @param: $callback   - this is the function that will be called when a message is found
    **/
    public function listen($strQueue, Closure $callback) : void
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

        $connection = $this->_getConnection(self::TYPE_READ);
        $channel = $connection->channel();

        $this->_declareExchange(self::TYPE_READ);

        $channel->queue_declare(
            $strQueue,
            $arrFlags[self::FLAG_PASSIVE] ?? false,
            $arrFlags[self::FLAG_DURABLE] ?? true,
            $arrFlags[self::FLAG_EXCLUSIVE] ?? false,
            $arrFlags[self::FLAG_AUTODELETE] ?? false
        );

        foreach ($arrKeys as $strQueueKey) {
            $strBindingKey = is_null($strQueueKey)?'#':$strQueueKey;
            $channel->queue_bind(
                $strQueue,
                $this->_strExchangeName,
                $strBindingKey
            );
        }

        $localCallback  = function (Message $message) use ($callback) {
            $strBody = $message->body;

            try {
                $arrData = Zend_Json::decode($strBody);
            } catch (Zend_Json_Exception $e) {
                echo 'Invalid Json: '.$strBody;
                ob_flush();
                flush();
                return;
            }

            $strClass = $message->get("type");

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
                $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
            } catch (Exception $e) {
                echo 'Caught Exception ('.get_class($e).'): '.$e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL;
            }
            ob_flush();
            flush();
        };

        $channel->basic_consume(
            $strQueue,
            "Consumer",
            false, // no local
            false, // no ack
            false, // exclusive
            false, // nowait
            $localCallback
        );

        register_shutdown_function(function () use ($channel, $connection) {
            $channel->close();
            $connection->close();
        });

        while (count($channel->callbacks)) {
            $channel->wait();
        }
    }

    /**
    *  Publishes the message
     *
    *  @param: $modelMessage
    **/
    public function publish(
        Hash $message,
        $strRoutingKey
    ) : void {

        $connection = $this->_getConnection(self::TYPE_READ);
        $channel = $connection->channel();

        $message = new Message(
            Zend_Json::encode($message),
            [
                'content_type' => 'application/json',
                'type' => get_class($message),
                'delivery_mode' => Message::DELIVERY_MODE_PERSISTENT
            ]
        );

        $this->_declareExchange(self::TYPE_WRITE);

        $channel->basic_publish(
            $message,
            $this->_strExchangeName,
            $strRoutingKey
        );

        $channel->close();
        $connection->close();
    }

    public function save(Hash $model) : void
    {
        $this->publish($model, $model->getRoutingKey());
    }
}
