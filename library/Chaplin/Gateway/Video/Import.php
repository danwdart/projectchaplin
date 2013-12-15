<?php
class Chaplin_Gateway_Video_Import
  extends Chaplin_Gateway_Abstract
{
	private $_daoExchange;

	public function __construct(Chaplin_Dao_Amqp_Exchange $daoExchange)
	{
		$this->_daoExchange = $daoExchange;
	}

	public function import()
  {
      $queueName = 'import';
  		$callback = function(Chaplin_Model_Video_Import $msg) {
  		    $msg->process();
  		};
  		$this->_daoExchange->listen($queueName, $callback);
  }

  public function save(Chaplin_Model_Video_Import $modelImport)
  {
      return $this->_daoExchange->save($modelImport);
  }
}