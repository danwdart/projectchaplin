<?php
/**
 * This file is part of Project Chaplin.
 *
 * Project Chaplin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Project Chaplin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Project Chaplin. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    Project Chaplin
 * @author     Dan Dart
 * @copyright  2012-2013 Project Chaplin
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version    git
 * @link       https://github.com/dandart/projectchaplin
**/
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