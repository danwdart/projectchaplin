<?php
class Canddi_Dao_Amqp_Exchange
{
    const CONFIG_NAME = 'Name';
    const CONFIG_TYPE = 'Type';
    const CONFIG_FLAGS = 'Flags';
    const CONFIG_QUEUE = 'Queue';
    const CONFIG_QUEUE_NAME = 'Name';
    const CONFIG_QUEUE_KEYS = 'Keys';

    private $_strExchangeName;
    private $_strExchangeType;
    private $_arrExchange;

    public function __construct($strExchangeName)
    {
        if (is_null($strExchangeName)) {
            throw new Exception('Cannot create with blank exchange name');
        }

        $arrExchanges = Canddi_Helper_Config_RabbitMQ::getInstance()->getArrayExchanges();
        if (!isset($arrExchanges[$strExchangeName]) 
        || !is_array($arrExchanges[$strExchangeName])) {
            throw new Canddi_Dao_Exception_Message_ExchangeNotFound($strExchangeName);
        }
        $this->_arrExchange     = $arrExchanges[$strExchangeName];
        if (!isset($this->_arrExchange[self::CONFIG_NAME]))
            throw new Canddi_Dao_Exception_Message_ExchangeNameEmpty();
        if (!isset($this->_arrExchange[self::CONFIG_TYPE]))
            throw new Canddi_Dao_Exception_Message_ExchangeTypeEmpty();
    }
    /**
    *  This loads the RabbitConnection
    *  This is a little naughty because it relies on the default RabbitConnection already being created
    **/
    private function _getConnection($bWrite = false)
    {
        if (true === $bWrite)
            $rabbitCon = Rabbit_Connection::getDefaultWriteConnection();
        else
            $rabbitCon		= Rabbit_Connection::getDefaultConnection();

        if (is_null($rabbitCon))
            throw new Canddi_Dao_Exception_Message_ConnectionEmpty();
        return $rabbitCon;
    }
    
    private function _getExchange($bWrite = false)
    {
        $rabbitCon = $this->_getConnection($bWrite);

        $arrFlags = (isset($this->_arrExchange[self::CONFIG_FLAGS]))?$this->_arrExchange[self::CONFIG_FLAGS]:array();
        $objFlags = new Rabbit_Flags($arrFlags);
        return $rabbitCon->getExchange($this->_arrExchange[self::CONFIG_NAME]
            ,$this->_arrExchange[self::CONFIG_TYPE]
            ,$objFlags                   
        );
    }
    /**
    *  Provides the queue listening functionality
    *  @param: $queueName
    *  @param: $callback   - this is the function that will be called when a message is found
    **/
    public function listen($strQueue, Closure $callback)
    {
        if (!isset($this->_arrExchange[self::CONFIG_QUEUE]) || !is_array($this->_arrExchange[self::CONFIG_QUEUE][$strQueue]))
            throw new Canddi_Dao_Exception_Message_QueueNotFound($strQueue);

        $arrQueue       = $this->_arrExchange[self::CONFIG_QUEUE][$strQueue];

        if(!isset($arrQueue[self::CONFIG_QUEUE_NAME])) {
            throw new Canddi_Dao_Exception_Message_QueueNotFound($strQueue.', '.self::CONFIG_QUEUE_NAME);           
        }

        if(!isset($arrQueue[self::CONFIG_QUEUE_KEYS]) || !is_array($arrQueue[self::CONFIG_QUEUE_KEYS])) {
            throw new Canddi_Dao_Exception_Message_QueueNotFound($strQueue.', '.self::CONFIG_QUEUE_KEYS);  
        }

        $queueName = $arrQueue[self::CONFIG_QUEUE_NAME];
        $queueKeys = $arrQueue[self::CONFIG_QUEUE_KEYS];

        $arrFlags = (isset($arrQueue[self::CONFIG_FLAGS]))?$arrQueue[self::CONFIG_FLAGS]:array();
        $objFlags = new Rabbit_Flags($arrFlags);	
        $rabbitCon = $this->_getConnection();
        $rabbitQueue    = $rabbitCon->getQueue($queueName, $objFlags);
        
        /**
        *  We need to convert a Rabbit_Message into a Canddi_Message
        **/
        $localCallback  = function(Rabbit_Message $rabbitMsg) use ($callback) {
            $modelMessage = Canddi_Message_Abstract::createFromDao($rabbitMsg);
            $callback($modelMessage);
        };

        // Make sure the exchange is created so there's something to listen on - DO NOT DELETE THIS
        $this->_getExchange(false);

        foreach($queueKeys as $strQueueKey) {
            $strRoutingKey = is_null($strQueueKey)?Canddi_Message_Abstract::WORDS_SKIP:$strQueueKey;
            $rabbitQueue->bind($this->_arrExchange[self::CONFIG_QUEUE_NAME], $strRoutingKey);
        }
        
        $rabbitQueue->consume($localCallback, $queueName);
    }

    /**
    *  Publishes the message
    *  @param: $modelMessage
    **/
    public function publish(Canddi_Message_Abstract $modelMessage = null)
    {
        if (is_null($modelMessage))
            throw new Canddi_Dao_Exception_Message_MessageEmpty();
        $strRoutingKey		= $modelMessage->getRoutingKey();
        $strMessageBody		= $modelMessage->getMessageBody();
        $arrMsgProperties	= $modelMessage->getMessageProperties();
        $rabbitMessage		= new Rabbit_Message($strMessageBody, $arrMsgProperties);
        $rabbitExchange     = $this->_getExchange(true)->publish($rabbitMessage, $strRoutingKey);
    }
}
