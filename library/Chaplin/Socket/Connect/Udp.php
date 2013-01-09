<?php
class Chaplin_Socket_Connect_Udp
	extends Chaplin_Socket_Connect_Abstract
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
}