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
        $listener = Chaplin_Socket_Listen_Tcp::create('0.0.0.0', 12345);

        Chaplin_Socket_Listen_Client::setOnRead(function($strData, $socket) use ($listener) {
            echo 'Client message: ('.$strData.')'.PHP_EOL;
            ob_flush();
            flush();
            $socket->write('Echo: '.$strData.PHP_EOL);

        });

        Chaplin_Socket_Listen_Client::setOnConnect(function($socket) use ($listener) {
            $listener->broadcast('New client coming online'.PHP_EOL);
            echo 'Client connected'.PHP_EOL;
            ob_flush();
            flush();
            $socket->write('Hello!'.PHP_EOL);
        });        

        Chaplin_Socket_Listen_Client::setOnDisconnect(function($socket) use ($listener) {
            echo 'Client disconnected'.PHP_EOL;
            ob_flush();
            flush();
        });
       
        $listener->listen();
    }

    public function broadcastAction()
    {
        $listener = Chaplin_Socket_Listen_Tcp::create('0.0.0.0', 12345);

        Chaplin_Socket_Listen_Client::setOnRead(function($strData, $socket) use ($listener) {
            echo 'Client message: ('.$strData.')'.PHP_EOL;
            if ('PONG' == $strData) {
                echo 'Received a pong'.PHP_EOL;
                ob_flush();
                flush();
            }
        });

        Chaplin_Socket_Listen_Client::setOnConnect(function($socket) use ($listener) {
            Chaplin_Async::setTimeout(5, function() use($socket) {
                if(!$socket->isConnected()) {
                    return;
                }
                echo 'Sending a ping'.PHP_EOL;
                ob_flush();
                flush();
                $socket->write('PING'.PHP_EOL);
                sleep(5);
            });
        });        

        Chaplin_Socket_Listen_Client::setOnDisconnect(function($socket) use ($listener) {
        });
       
        $listener->listen();
    }
}

