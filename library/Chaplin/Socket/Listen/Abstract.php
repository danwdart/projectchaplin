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
abstract class Chaplin_Socket_Listen_Abstract
	extends Chaplin_Socket_Abstract
{
	private $_arrClients;

	protected function __construct($strHost, $intPort)
	{
		$this->_strHost = $strHost;
		$this->_intPort = $intPort;
		// TODO check for IPv6
		$this->_resourceSocket = socket_create(AF_INET, $this->_getProtocolType(), $this->_getProtocol());
	}

	abstract protected function _getProtocol();

	public function bind()
	{
		socket_set_option($this->_resourceSocket, SOL_SOCKET, SO_REUSEADDR, 1);	
		$this->_bBound = socket_bind($this->_resourceSocket, '0.0.0.0', $this->_intPort);
		if (!$this->_bBound) {
			$this->_exceptionError();
		}
	}

	
}