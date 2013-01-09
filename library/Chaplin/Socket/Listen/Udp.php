<?php
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