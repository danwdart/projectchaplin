<?php
abstract class Chaplin_Socket_Abstract
{
	const CRLF = "\r\n";

	protected $_resourceSocket;

	protected $_strHost;
	protected $_intPort;

	protected $_bConnected = false;
	protected $_bBound = false;

	protected static function _createId($strHost, $intPort)
	{
		return $strHost.':'.$intPort;
	}

	protected function _getProtocolType()
	{
		switch($this->_getProtocol()) {
			case SOL_TCP:
				return SOCK_STREAM;
			case SOL_UDP:
				return SOCK_DGRAM;
			default:
				return SOCK_RAW;
		}
	}

	protected function _exceptionError()
	{
		throw new Exception(
			socket_strerror(socket_last_error()),
			socket_last_error()
		);
	}

	public function isConnected()
	{
		return $this->_bConnected;
	}

	public function disconnect()
	{
		socket_close($this->_resourceSocket);
		$this->_bConnected = false;
	}

	public function readText($intLength)
	{
		$strResponse = socket_read($this->_resourceSocket, $intLength, PHP_NORMAL_READ);
		if (false === $strResponse) {
			$this->_exceptionError();
		}
		return $strResponse;
	}

	public function readBinary($intLength)
	{
		$strResponse = socket_read($this->_resourceSocket, $intLength, PHP_BINARY_READ);
		if (false === $strResponse) {
			$this->_exceptionError();
		}
		return $strResponse;
	}

	public function write($strText)
	{
		$intLength = strlen($strText);
		
		$intSent = socket_write($this->_resourceSocket, $strText);

		if (false === $intSent) {
			$this->_exceptionError();
		}
		if ($intLength !== $intSent) {
			throw new Exception('Sent only ('.$intSent.') bytes of ('.$intLength.') total');
		}
	}
}