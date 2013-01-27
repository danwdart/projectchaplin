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
class Chaplin_Socket_Listen_Tcp
	extends Chaplin_Socket_Listen_Abstract
{
	const PROTOCOL = SOL_TCP;

	private static $_arrConnections;

	private $_arrClients = array();

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

	public function broadcast($strText)
	{
		if(empty($this->_arrClients)) {
			return;
		}
		foreach($this->_arrClients as $socketClient) {
			$client = new Chaplin_Socket_Listen_Client($socketClient);
			$client->write($strText);
		}
	}

	public function listen()
	{
		if (!$this->_bBound) {
			$this->bind();
		}
		$this->_bConnected = socket_listen($this->_resourceSocket);
		if (!$this->_bConnected) {
			$this->_exceptionError();
		}
		socket_set_nonblock($this->_resourceSocket);

		while(true) {
			$socketClient = @socket_accept($this->_resourceSocket);
			if (is_resource($socketClient)) {
				$client = new Chaplin_Socket_Listen_Client($socketClient);
				$client->onConnect();
				$this->_arrClients[] = $socketClient;
			}

			foreach($this->_arrClients as $idxClient => $resClient) {
				$client = new Chaplin_Socket_Listen_Client($resClient);
				if (@socket_recv($resClient, $string, 1024, MSG_DONTWAIT) === 0) {
					$client->onDisconnect();
					unset($this->_arrClients[$idxClient]);
					socket_close($resClient);
				} else {
					if (!empty($string)) {
						$string = trim($string);
						$client->onRead($string);
					}
				}
			}
		}
		socket_close($this->_resourceSocket);
	}
}