<?php
class Amqp_Channel
{
    private $_amqpConnection;
    private $_amqpChannel;

    public function __construct(Amqp_Connection $amqpConnection)
    {
        $this->_amqpConnection = $amqpConnection->getConnection();
        $this->_amqpChannel = new AMQPChannel($amqpConnection->getConnection);
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
