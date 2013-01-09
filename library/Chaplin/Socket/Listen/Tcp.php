<?php
class Chaplin_Socket_Listen_Tcp
	extends Chaplin_Socket_Listen_Abstract
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
		
		$arrResources = array($this->_resourceSocket);

		while(true) {
			$arrRead = $arrResources;
			$arrWrite = null;
			$arrExcept = null;

			$intChanges = socket_select($arrRead, $arrWrite, $arrExcept, 0);
			
			echo Zend_Json::encode(array($intChanges,$arrRead, $arrWrite, $arrExcept))."\r";
			ob_flush();
			flush();

			// returns number of changed
			if(0 === $intChanges) {
				continue;
			}
			if (false === $intChanges) {
				$this->_exceptionError();
			}
    		
			echo $intChanges;
			ob_flush();
			flush();

			// Anyone need to connect
			if (in_array($this->_resourceSocket, $arrRead)) {
				$socketClient = socket_accept($this->_resourceSocket);
				
				$this->_clients[] = $socketClient;

				$client = new Chaplin_Socket_Listen_Client($socketClient);			
				$client->onConnect();

				$intIdx = array_search($socketClient, $arrRead);
				unset($arrRead[$intIdx]);
			}

			$arrRead = array();

			// Need to read from any left
			foreach($arrRead as $resourceSocketReader) {
				$client = new Chaplin_Socket_Listen_Client($resourceSocketReader);
				$strData = @socket_read($resourceSocketReader, 1024, PHP_NORMAL_READ);
				// disconnected
				if(false === $strData) {
					$intIdx = array_search($resourceSocketReader, $this->_clients);
					unset($this->_clients[$intIdx]);
					$client->onDisconnect();
					continue;
				}

				// Have data to read
				$strData = trim($strData);
				if (empty($strData)) {
					continue;
				}
				$client->onRead($strData);
			}
		}
		socket_close($this->_resourceSocket);
	}
}