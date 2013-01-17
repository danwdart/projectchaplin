<?php
class Chaplin_Dao_Amqp_Exchange
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
        
        if (!isset($arrExchanges[$strExchangeName]) ||
            !is_array($arrExchanges[$strExchangeName])) {
            throw new Exception($strExchangeName.' exchange not found');
        }
        
        $this->_arrExchange = $arrExchanges[$strExchangeName];

        if (!isset($this->_arrExchange[self::CONFIG_TYPE]))
            throw new Canddi_Dao_Exception_Message_ExchangeTypeEmpty();
    }
    
    private static function _getReadConnection()
    {
        if (is_null(self::$_amqpConnectionRead)) {
            $arrReadConfig = Chaplin_Config_Servers::getInstance()
            ->getConfigConnectionRead();

            self::$_amqpConnectionRead = new Amqp_Connection($arrReadConfig);
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
            $arrWriteConfig = Chaplin_Config_Servers::getInstance()
            ->getConfigConnectionWrite();

            self::$_amqpConnectionWrite = new Amqp_Connection($arrWriteConfig);
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
            
        $intFlags = Amqp_Flags::getFlags($arrFlags);
        $amqpChannel = new Amqp_Channel($amqpConnection);
        $exchange = new Amqp_Exchange($amqpChannel);
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
            
        $intFlags = Amqp_Flags::getFlags($arrFlags);
        $amqpChannel = new Amqp_Channel($amqpConnection);
        $exchange = new Amqp_Exchange($amqpChannel);
        $exchange->setName($this->_strExchangeName);
        $exchange->setType($this->_arrExchange[self::CONFIG_TYPE]);
        $exchange->setFlags($intFlags);
        $exchange->declareExchange();
        return $exchange;
    }
    
    /**
    *  Provides the queue listening functionality
    *  @param: $queueName
    *  @param: $callback   - this is the function that will be called when a message is found
    **/
    public function listen($strQueue, Closure $callback)
    {
        if (!isset($this->_arrExchange[self::CONFIG_QUEUES]) ||
           !is_array($this->_arrExchange[self::CONFIG_QUEUES][$strQueue])) {
            throw new Exception('Queue not found: '.$strQueue);
        }

        $arrQueue = $this->_arrExchange[self::CONFIG_QUEUES][$strQueue];

        if (!isset($arrQueue[self::CONFIG_QUEUE_KEYS]) ||
            !is_array($arrQueue[self::CONFIG_QUEUE_KEYS])) {
            throw new Exception($strQueue.' has no keys');
        }

        $arrKeys = $arrQueue[self::CONFIG_QUEUE_KEYS];

        $arrFlags = (isset($arrQueue[self::CONFIG_FLAGS]))?
            $arrQueue[self::CONFIG_FLAGS]:
            array();
            
        $intFlags = Amqp_Flags::getFlags($arrFlags);
        $amqpConnection = $this->_getReadConnection();
        
        $amqpChannel = new Amqp_Channel($amqpConnection);
        $amqpQueue = new Amqp_Queue($amqpChannel);
        $amqpQueue->setName($strQueue);
        $amqpQueue->setFlags($intFlags);
        $amqpQueue->declareQueue();
        
        $localCallback  = function(Amqp_Envelope $amqpEnvelope) use ($callback) {
            $modelMessage = Chaplin_Message_Abstract::createFromDao($amqpEnvelope);
            $callback($modelMessage);
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
    *  @param: $modelMessage
    **/
    public function publish(Chaplin_Message_Abstract $message, $strRoutingKey)
    {
        //$strRoutingKey		= $modelMessage->getRoutingKey();
        //$strMessageBody		= $modelMessage->getMessageBody();
        //$arrMsgProperties	= $modelMessage->getMessageProperties();
        
        $this->_getWriteExchange()
            ->publish(Zend_Json::encode($message), $strRoutingKey);
    }
}
