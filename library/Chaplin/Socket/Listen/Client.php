<?php
class Chaplin_Socket_Listen_Client
	extends Chaplin_Socket_Abstract
{
	private static $_onRead;
	private static $_onDisconnect;
	private static $_onConnect;

	public function __construct($resourceSocket)
	{
		$this->_resourceSocket = $resourceSocket;
	}

	public static function setOnRead(Closure $closure)
	{
		self::$_onRead = $closure;
	}

	public static function setOnDisconnect(Closure $closure)
	{
		self::$_onDisconnect = $closure;
	}

	public static function setOnConnect(Closure $closure)
	{
		self::$_onConnect = $closure;
	}

	public function onRead($strData)
	{
		echo __METHOD__.PHP_EOL;
		if(is_null(self::$_onRead)) {
			return;
		}
		$callback = self::$_onRead;
		$callback($strData, $this);
	}

	public function onDisconnect()
	{
		echo __METHOD__.PHP_EOL;
		if(is_null(self::$_onDisconnect)) {
			return;
		}
		$callback = self::$_onDisconnect;
		$callback($this);
	}

	public function onConnect()
	{
		echo __METHOD__.PHP_EOL;
		if(is_null(self::$_onConnect)) {
			return;
		}
		$callback = self::$_onConnect;
		$callback($this);
	}
}