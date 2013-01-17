<?php
class Chaplin_Service_Amqp_Notification
    extends Chaplin_Service_Amqp_Abstract
{
    const EXCHANGE_NAME = 'Notification';

    public function notification()
    {
      $queueName = 'notification';
  		$callback = function(Chaplin_Message_Notification_Abstract $msg) {
  		    $msg->process();
  		};
  		$this->_listen($queueName, $callback);
    }
}
