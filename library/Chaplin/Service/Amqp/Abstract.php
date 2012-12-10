<?php
abstract class Chaplin_Service_Amqp_Abstract
{
    private $_daoExchange;

    public function __construct(Chaplin_Dao_Amqp_Exchange $daoExchange)
    {
        $this->_daoExchange = $daoExchange;
    }
    
    public function debug()
    {
  		$queueName = 'debug';
  		$callback = function($thingy) {
  		    var_dump($thingy);
  		};
  		$this->_listen($queueName, $callback);
    }

    protected function _listen($queueName, Closure $callback)
    {
        $this->_daoExchange->listen($queueName, $callback);
    }
    
    public function publishMessage($strThingy, $strRoutingKey)
    {
        $this->_daoExchange->publish($strThingy, $strRoutingKey);
    }
}
