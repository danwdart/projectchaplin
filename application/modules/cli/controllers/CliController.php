<?php
class CliController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function cliAction()
    {
        Chaplin_Service::getInstance()
            ->getExchange('Video')
            ->debugMessage();
    }
    
    public function debugAction()
    {
        Chaplin_Service::getInstance()
            ->getExchange('Video')
            ->debug();
    }
    
    public function processAction()
    {
        Chaplin_Service::getInstance()
            ->getExchange('Video')
            ->process();
    }       

    public function telnetudpAction()
    {
        $listener = Chaplin_Socket_Listen_Udp::create('0.0.0.0', 1234);
        $listener->listen(function($strText, Closure $closureSend) {
            echo $strText.PHP_EOL;
            $closureSend('Echo: ('.$strText.')'.PHP_EOL);
            ob_flush();
            flush(); 
        });
    }

    public function telnettcpAction()
    {
        Chaplin_Socket_Listen_Client::setOnRead(function($strData, $socket) {
            echo 'Client message: ('.$strData.')'.PHP_EOL;
            ob_flush();
            flush();
            $socket->write('Echo: '.$strData.PHP_EOL);

        });

        Chaplin_Socket_Listen_Client::setOnConnect(function($socket) {
            echo 'Client connected'.PHP_EOL;
            ob_flush();
            flush();
            $socket->write('Hello!'.PHP_EOL);
        });        

        Chaplin_Socket_Listen_Client::setOnDisconnect(function($socket) {
            echo 'Client disconnected'.PHP_EOL;
            ob_flush();
            flush();
        });

        $listener = Chaplin_Socket_Listen_Tcp::create('0.0.0.0', 12345);
        $listener->listen();
    }
}

