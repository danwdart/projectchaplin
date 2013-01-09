<?php
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