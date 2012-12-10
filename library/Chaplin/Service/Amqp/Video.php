<?php
class Chaplin_Service_Amqp_Video
    extends Chaplin_Service_Amqp_Abstract
{
    const EXCHANGE_NAME = 'Video';

    public function debugMessage()
    {
        $this->publishMessage('Hello World!', 'thingy');
    }
}
