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
  		$callback = function(Chaplin_Message_Abstract $msg) {
  		    var_dump($msg);
 		    ob_flush();
  		    flush();
  		    die();
  		};
  		$this->_listen($queueName, $callback);
    }

    protected function _listen($queueName, Closure $callback)
    {
        $callbackEx = function(Chaplin_Message_Abstract $msg) use($callback) {
            try {
                $callback($msg);
            } catch(Exception $e) {
                echo $e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL;
                ob_flush();
                flush();
            }
        };
        $this->_daoExchange->listen($queueName, $callbackEx);
    }
    
    public function publishMessage($strThingy, $strRoutingKey)
    {
        $this->_daoExchange->publish($strThingy, $strRoutingKey);
    }
}
