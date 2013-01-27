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
class Amqp_Channel
{
    private $_amqpConnection;
    private $_amqpChannel;

    public function __construct(Amqp_Connection $amqpConnection)
    {
        $this->_amqpConnection = $amqpConnection->getConnection();
        $this->_amqpChannel = new AMQPChannel($amqpConnection->getConnection());
    }

    public function getAMQPChannel()
    {
        return $this->_amqpChannel;
    }

    public function commitTransaction()
    {
        return $this->_amqpChannel->commitTransaction();
    }
    
    public function isConnected()
    {
        return $this->_amqpChannel->isConnected();
    }
    
    public function qos($intSize, $intCount)
    {
        return $this->_amqpChennel->qos($intSize, $intCount);
    }
    
    public function callbackTransaction()
    {
        return $this->_amqpChannel->callbackTransation();
    }
    
    public function setPrefetchCount($intCount)
    {
        return $this->_amqpChannel->setPrefetchCount($intCount);
    }
    
    public function setPrefetchSize($intSize)
    {
        return $this->_amqpChannel->setPrefetchSize($intSize);
    }
    
    public function startTransaction()
    {
        return $this->_amqpChannel->startTransaction();
    }
}
