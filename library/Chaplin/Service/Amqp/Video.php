<?php
class Chaplin_Service_Amqp_Video
    extends Chaplin_Service_Amqp_Abstract
{
    const EXCHANGE_NAME = 'Video';

    public function encode()
    {
      $queueName = 'encode';
  		$callback = function(Chaplin_Message_Video_Convert $msg) {
  		    $msg->process();
  		};
  		$this->_listen($queueName, $callback);
    }

    public function youtube()
    {
      $queueName = 'youtube';
      $callback = function(Chaplin_Message_Video_YouTube $msg) {
          $msg->process();
      };
      $this->_listen($queueName, $callback);
    }
}
