<?php
abstract class Chaplin_Socket_Connect_Abstract
	extends Chaplin_Socket_Abstract
{
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
		$this->_bBound = socket_bind($this->_resourceSocket, $this->_strHost);
		if (!$this->_bBound) {
			$this->_exceptionError();
		}
	}

	
}