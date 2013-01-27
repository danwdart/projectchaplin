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
	private static $_onRead;
	private static $_onDisconnect;
	private static $_onConnect;

	public function __construct($resourceSocket)
	{
		$this->_resourceSocket = $resourceSocket;
	}

	public static function setOnRead(Closure $closure)
	{
		self::$_onRead = $closure;
	}

	public static function setOnDisconnect(Closure $closure)
	{
		self::$_onDisconnect = $closure;
	}

	public static function setOnConnect(Closure $closure)
	{
		self::$_onConnect = $closure;
	}

	public function onRead($strData)
	{
		echo __METHOD__.PHP_EOL;
		ob_flush();
		flush();
		if(is_null(self::$_onRead)) {
			return;
		}
		$callback = self::$_onRead;
		$callback($strData, $this);
	}

	public function onDisconnect()
	{
		echo __METHOD__.PHP_EOL;
		ob_flush();
		flush();
		if(is_null(self::$_onDisconnect)) {
			return;
		}
		$callback = self::$_onDisconnect;
		$callback($this);
	}

	public function onConnect()
	{
		echo __METHOD__.PHP_EOL;
		ob_flush();
		flush();
		if(is_null(self::$_onConnect)) {
			return;
		}
		$callback = self::$_onConnect;
		$callback($this);
	}
}