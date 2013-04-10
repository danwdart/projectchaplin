<?php
class Chaplin_Gateway_Video_Convert
{
	private $_daoExchange;

	public function __construct(Chaplin_Dao_Amqp_Exchange $daoExchange)
	{
		$this->_daoExchange = $daoExchange;
	}

	public function convert()
  {
      $queueName = 'convert';
  		$callback = function(Chaplin_Model_Video_Convert $msg) {
  		    $msg->process();
  		};
  		$this->_daoExchange->listen($queueName, $callback);
  }

  public function save(Chaplin_Model_Video_Convert $modelConvert)
  {
      return $this->_daoExchange->save($modelConvert);
  }
}