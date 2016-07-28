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

    public function convertAction()
    {
        Chaplin_Gateway::getInstance()
            ->getVideo_Convert()
            ->convert();
    }

    public function importAction()
    {
        Chaplin_Gateway::getInstance()
            ->getVideo_Import()
            ->import();
    }

    public function youtubeAction()
    {
        Chaplin_Gateway::getInstance()
            ->getVideo_Youtube()
            ->youtube();
    }

    public function vimeoAction()
    {
        Chaplin_Gateway::getInstance()
            ->getVideo_Vimeo()
            ->vimeo();
    }

    public function httpAction()
    {
        $listener = Chaplin_Socket_Listen_Tcp::create('0.0.0.0', 80);
        $listener->listen(function(Chaplin_Socket_Listen_Client $client) use ($listener) {
            $text = [];
            $client->onRead(function($strData) use ($client) {
                $arrHeaders = [
                     'HTTP/1.1 200 Hunky Dory',
                     'Content-Type: text/html',
                     'Connection: close'
                ];
                $strData = '<h1>You sent</h1><p>'.str_replace("\r\n", '<br/>', $strData);
                $client->write(implode("\r\n", $arrHeaders)."\r\n\r\n".$strData."\r\n\r\n");
                $client->disconnect();
                $client->onRead(function($strData) use ($client) {});
            });
        });
    }

    public function emailAction()
    {
        $modelUser = Chaplin_Gateway::getInstance()->getUser()->getByUsername('dan');
        Chaplin_Gateway::getInstance()->getEmail()->email(
            $modelUser,
            'Hi',
            'email',
            ['who' => 'World']
        );
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

    public function ircbotAction()
    {
        $strVhost = Chaplin_Config_Servers::getInstance()->getVhost();

        $socket = Chaplin_Socket_Connect_Tcp::create('irc.megworld.co.uk', 6667)
            ->bind()
            ->connect()
            ->waitFor('/Found your hostname/')
            ->send('NICK ChaplinBot')
            ->send('USER ChaplinBot '.$strVhost.' projectchaplin :Chaplin Bot')
            ->waitFor('/376/')
            ->send('JOIN #bots')
            ->waitFor('/396/')
            ->waitFor('/Welcome/')
            ->send('PRIVMSG #bots :I am a bot. Tra la la la.')
            ->waitFor('/what now/')
            ->send('PRIVMSG #bots :I am leaving now.')
            ->send('QUIT :I\'m leaving now');
    }

    public function getpageAction()
    {
        $socket = Chaplin_Socket_Connect_Tcp::create('dandart.co.uk', 80)
            ->bind()
            ->connect()
            ->send('GET / HTTP/1.1')
            ->send('Host: dandart.co.uk')
            ->send('');
        do {
            $response = $socket->readText(1024);
            echo $response;
            ob_flush();
        } while ('' !== $response);
        $socket->disconnect();
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
