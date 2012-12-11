<?php
class Chaplin_Service_Amqp_Video
    extends Chaplin_Service_Amqp_Abstract
{
    const EXCHANGE_NAME = 'Video';

    public function process()
    {
        $queueName = 'encode';
  		$callback = function(Chaplin_Message_Video_Convert $msg) {
  		    $msg->process();
  		};
  		$this->_listen($queueName, $callback);
    }
}
