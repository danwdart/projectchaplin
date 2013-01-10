<?php
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