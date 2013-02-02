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
class Chaplin_Socket_Listen_Client
	extends Chaplin_Socket_Abstract
{
	private $_onRead;
	private $_onDisconnect;
	private $_onConnect;

	public function __construct($resourceSocket)
	{
		$this->_resourceSocket = $resourceSocket;
	}

	public function getResource()
	{
		return $this->_resourceSocket;
	}

	public function onRead(Callable $closure)
	{
		$this->_onRead = $closure;
		return $this;
	}

	public function onDisconnect(Callable $closure)
	{
		$this->_onDisconnect = $closure;
		return $this;
	}

	public function invokeRead($strData)
	{
		echo __METHOD__.PHP_EOL;
		ob_flush();
		flush();
		if(is_null($this->_onRead)) {
			echo 'No read'.PHP_EOL; ob_flush();flush();
			return;
		}
		$callback = $this->_onRead;
		$callback($strData);
		return $this;
	}

	public function invokeDisconnect()
	{
		echo __METHOD__.PHP_EOL;
		ob_flush();
		flush();
		if(is_null($this->_onDisconnect)) {
			echo 'No disconnect'.PHP_EOL; ob_flush();flush();
			return;
		}
		$callback = $this->_onDisconnect;
		$callback();
		return $this;
	}
}