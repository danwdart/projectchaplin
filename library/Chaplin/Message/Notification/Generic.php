<?php
class Chaplin_Message_Notification_Generic
	extends Chaplin_Message_Notification_Abstract
{
	const TYPE = 'Generic';

	public static function create()
	{
		$msg = new self();
		$msg->_setField(self::FIELD_TITLE, 'Generic Message');
		$msg->_setField(self::FIELD_USERNAME, 'dan');
		$msg->_setField(self::FIELD_MAILTEMPLATE, 'generic');
		$msg->_setField(self::FIELD_PARAMS, array('who' => 'world'));
		return $msg;
	}

	public function getType()
	{
		return self::TYPE;
	}
}