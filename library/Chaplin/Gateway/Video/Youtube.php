<?php
class Chaplin_Gateway_Video_Youtube
    extends Chaplin_Gateway_Abstract
{
	private $_daoExchange;

	public function __construct(Chaplin_Dao_Amqp_Exchange $daoExchange)
	{
		$this->_daoExchange = $daoExchange;
	}

    public function youtube()
    {
      $queueName = 'youtube';
      $callback = function(Chaplin_Model_Video_Youtube $msg) {
          $msg->process();
      };
      $this->_daoExchange->listen($queueName, $callback);
    }

    public function save(Chaplin_Model_Video_Youtube $modelYoutube)
    {
        return $this->_daoExchange->save($modelYoutube);
    }
}