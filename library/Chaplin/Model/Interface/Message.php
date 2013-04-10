<?php
interface Chaplin_Model_Interface_Message
{
	public function getExchangeName();

	public function getRoutingkey();

	public function process();
}