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
class Chaplin_Socket_Listen_Udp
	extends Chaplin_Socket_Listen_Abstract
{
	const PROTOCOL = SOL_UDP;

	private static $_arrConnections;

	public static function create($strHost, $intPort)
	{
		$strId = self::_createId($strHost, $intPort);
		if(isset(self::$_arrConnections[$strId])) {
			return self::$_arrConnections[$strId];
		}

		self::$_arrConnections[$strId] = new self($strHost, $intPort);
		return self::$_arrConnections[$strId];
	}

	protected function _getProtocol()
	{
		return self::PROTOCOL;
	}

	public function listen(Closure $closure)
	{
		if (!$this->_bBound) {
			$this->bind();
		}

		while(true) {
			$str = null;
			$length = 0;
			$strHost = '';
			$strPort = 0;
			$intBytes = socket_recvfrom($this->_resourceSocket, $str, 1024, 0, $strHost, $strPort);
			if (false === $intBytes) {
				$this->_exceptionError();
			}
			if(!empty($str)) {
				$str = trim($str);
				$socket = $this->_resourceSocket;
				$closureReply = function($strText) use ($socket, $strHost, $strPort) {
					socket_sendto($socket, $strText, strlen($strText), 0, $strHost, $strPort);
				};
				$closure($str, $closureReply);				
			}
		}
	}
}