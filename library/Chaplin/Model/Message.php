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
class Chaplin_Model_Message
    extends Chaplin_Model_Field_Hash
{
    const FIELD_MESSAGEID = '_id';
    const FIELD_RECIPIENT = 'Recipient';
    const FIELD_SENDER = 'Sender';
    const FIELD_SUBJECT = 'Subject';
    const FIELD_TEXT = 'Text';
    const FIELD_DATE_TIMECREATED = 'TimeCreated';
    const FIELD_DATE_TIMEACKNOWLEDGED = 'TimeAcknowledged';
    const FIELD_DATE_TIMEDELETED = 'TimeDeleted';
    const FIELD_PRIORITY = 'Priority';

    const PRIORITY_HIGH = 1;
    const PRIORITY_NORMAL = 0;
    const PRIORITY_LOW = -1;

    protected $_arrFields = [
        self::FIELD_MESSAGEID => ['Class' => 'Chaplin_Model_Field_FieldId'],
        self::FIELD_RECIPIENT => ['Class' => 'Chaplin_Model_Field_Field'],
        self::FIELD_SENDER => ['Class' => 'Chaplin_Model_Field_Field'],
        self::FIELD_SUBJECT => ['Class' => 'Chaplin_Model_Field_Field'],
        self::FIELD_TEXT => ['Class' => 'Chaplin_Model_Field_Field'],
        self::FIELD_DATE_TIMECREATED => ['Class' => 'Chaplin_Model_Field_Field'],
        self::FIELD_DATE_TIMEACKNOWLEDGED => ['Class' => 'Chaplin_Model_Field_Field'],
        self::FIELD_DATE_TIMEDELETED => ['Class' => 'Chaplin_Model_Field_Field'],
        self::FIELD_PRIORITY => ['Class' => 'Chaplin_Model_Field_Field'],
    ];

    public static function create(
        Chaplin_Model_User $modelUserRecipient,
        Chaplin_Model_User $modelUserSender,
        $strSubject,
        $strText,
        $intPriority = self::PRIORITY_NORMAL
    ) {
        $modelMessage = new self();
        $modelMessage->_setField(self::FIELD_MESSAGEID, md5(uniqid()));
        $modelMessage->_setField(self::FIELD_RECIPIENT, $modelUserRecipient->getUsername());
        $modelMessage->_setField(self::FIELD_SENDER, $modelUserSender->getUsername());
        $modelMessage->_setField(self::FIELD_SUBJECR, $strSubject);
        $modelMessage->_setField(self::FIELD_TEXT, $strText);
        $modelMessage->_setField(self::FIELD_DATE_TIMECREATED, time();
        $modelMessage->_setField(self::FIELD_PRIORITY, $intPriority);
        return $modelMessage;
    }
    
    public function delete()
    {
        return Chaplin_Gateway::getInstance()->getUser()->delete($this);
    }

    public function save()
    {
        Chaplin_Gateway::getInstance()->getUser()->save($this);
        // send a message
    }
