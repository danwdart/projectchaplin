<?php
/**
 * This file is part of Project Chaplin.
 *
 * Project Chaplin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Project Chaplin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Project Chaplin. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    Project Chaplin
 * @author     Dan Dart
 * @copyright  2012-2013 Project Chaplin
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version    git
 * @link       https://github.com/dandart/projectchaplin
**/
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

    public function sendAction()
    {
        Chaplin_Message_Notification_Generic::create()->send();
    }

    public function notificationAction()
    {
        Chaplin_Service::getInstance()
            ->getExchange('Notification')
            ->notification();
    }
    
    public function encodeAction()
    {
        Chaplin_Service::getInstance()
            ->getExchange('Video')
            ->encode();
    }       

    public function youtubeAction()
    {
        Chaplin_Service::getInstance()
            ->getExchange('Video')
            ->youtube();
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

        $listener->listen(function(Chaplin_Socket_Listen_Client $client) use ($listener) {
            $listener->broadcast('New client coming online'.PHP_EOL);

            echo 'Client connected'.PHP_EOL;
            ob_flush();
            flush();
            $client->write('Hello!'.PHP_EOL)
            ->onRead(function($strData) use ($client) {
                echo 'Client message: ('.$strData.')'.PHP_EOL;
                ob_flush();
                flush();
                $client->write('Echo: '.$strData.PHP_EOL);
            })
            ->onDisconnect(function() use ($client) {
                echo 'Client disconnected'.PHP_EOL;
                ob_flush();
                flush();
            });
        });
    }

    public function broadcastAction()
    {
        $listener = Chaplin_Socket_Listen_Tcp::create('0.0.0.0', 12345);

        Chaplin_Socket_Listen_Client::setOnRead(function($strData, $socket) use ($listener) {
            echo 'Client message: ('.$strData.')'.PHP_EOL;
            if (0 === strpos($strData, 'PONG')) {
                echo 'Received a pong: ('.$strData.')'.PHP_EOL;
                ob_flush();
                flush();
            }
        });

        Chaplin_Socket_Listen_Client::setOnConnect(function($socket) use ($listener) {
            Chaplin_Async::setTimeout(5, function() use($socket) {
                echo 'Sending a ping'.PHP_EOL;
                ob_flush();
                flush();
                $socket->write('PING '.time().PHP_EOL);
                sleep(5);
            });
        });        

        Chaplin_Socket_Listen_Client::setOnDisconnect(function($socket) use ($listener) {
        });
       
        $listener->listen();
    }
}

