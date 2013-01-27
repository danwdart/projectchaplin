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
abstract class Chaplin_Service_Amqp_Abstract
{
    private $_daoExchange;

    public function __construct(Chaplin_Dao_Amqp_Exchange $daoExchange)
    {
        $this->_daoExchange = $daoExchange;
    }
    
    public function debug()
    {
  		$queueName = 'debug';
  		$callback = function(Chaplin_Message_Abstract $msg) {
  		    var_dump($msg);
 		    ob_flush();
  		    flush();
  		    die();
  		};
  		$this->_listen($queueName, $callback);
    }

    protected function _listen($queueName, Closure $callback)
    {
        $callbackEx = function(Chaplin_Message_Abstract $msg) use($callback) {
            try {
                $callback($msg);
            } catch(Exception $e) {
                echo $e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL;
                ob_flush();
                flush();
            }
        };
        $this->_daoExchange->listen($queueName, $callbackEx);
    }
    
    public function publishMessage($strThingy, $strRoutingKey)
    {
        $this->_daoExchange->publish($strThingy, $strRoutingKey);
    }
}
