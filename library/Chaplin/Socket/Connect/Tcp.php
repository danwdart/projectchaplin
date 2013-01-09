<?php
class Chaplin_Socket_Connect_Tcp
	extends Chaplin_Socket_Connect_Abstract
{
	const PROTOCOL = SOL_TCP;

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

	public function connect()
	{
		if (!$this->_bBound) {
			throw new Exception('Bind before connecting');
		}
		$this->_bConnected = socket_connect($this->_resourceSocket, $this->_strHost, $this->_intPort);
		if (!$this->_bConnected) {
			$this->_exceptionError();
		}
		socket_set_block($this->_resourceSocket);
	}
}