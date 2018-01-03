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
 * @package   ProjectChaplin
 * @author    Dan Dart <chaplin@dandart.co.uk>
 * @copyright 2012-2018 Project Chaplin
 * @license   http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version   GIT: $Id$
 * @link      https://github.com/kathiedart/projectchaplin
**/
abstract class Chaplin_Model_Message
    extends Chaplin_Model_Field_Hash
{
    const FIELD_MESSAGEID = 'MessageId';
    const FIELD_MAILTEMPLATE = 'MailTemplate';
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
        self::FIELD_DATE_TIMECREATED => [
            'Class' => 'Chaplin_Model_Field_Field'
        ],
        self::FIELD_DATE_TIMEACKNOWLEDGED => [
            'Class' => 'Chaplin_Model_Field_Field'
        ],
        self::FIELD_DATE_TIMEDELETED => [
            'Class' => 'Chaplin_Model_Field_Field'
        ],
        self::FIELD_PRIORITY => ['Class' => 'Chaplin_Model_Field_Field'],
    ];

    public static function create(
        Chaplin_Model_User $modelUserRecipient,
        Chaplin_Model_User $modelUserSender,
        $strSubject,
        $strText,
        $intPriority = self::PRIORITY_NORMAL
    )
    {

        $modelMessage = new self();
        $modelMessage->_setField(self::FIELD_MESSAGEID, md5(uniqid()));
        $modelMessage->_setField(
            self::FIELD_RECIPIENT,
            $modelUserRecipient->getUsername()
        );
        $modelMessage->_setField(
            self::FIELD_SENDER,
            $modelUserSender->getUsername()
        );
        $modelMessage->_setField(self::FIELD_SUBJECT, $strSubject);
        $modelMessage->_setField(self::FIELD_TEXT, $strText);
        $modelMessage->_setField(self::FIELD_DATE_TIMECREATED, time());
        $modelMessage->_setField(self::FIELD_PRIORITY, $intPriority);
        return $modelMessage;
    }

    public function getId()
    {
        return $this->_getField(self::FIELD_MESSAGEID, null);
    }

    public function save()
    {
        Chaplin_Gateway::getInstance()->getMessage()->save($this);
        if ($this->bIsNew()) {
            $this->sendEmail();
        }
    }

    public function process()
    {
        $strUsername = $this->_getField(
            self::FIELD_RECIPIENT,
            null
        );

        if (is_null($strUsername)) {
            return;
        }

        try {
            $modelUser = Chaplin_Gateway::getInstance()
            ->getUser()
            ->getByUsername($strUsername);
        } catch(Exception $e) {
            return;
        }

        if (is_null($modelUser->getEmail())) {
            return;
        }

        $strPathToTemplateHtml = APPLICATION_PATH.
            '/../mustache/en_GB/mail/html/'.
            $this->_getField(self::FIELD_MAILTEMPLATE, null).
            '.mustache';

        $strPathToTemplateText = APPLICATION_PATH.
            '/../mustache/en_GB/mail/text/'.
            $this->_getField(self::FIELD_MAILTEMPLATE, null).
            '.mustache';

        $strTemplateHtml = file_get_contents($strPathToTemplateHtml);
        $strTemplateText = file_get_contents($strPathToTemplateText);
        $m = new Mustache_Engine;

        $strMessageHtml = $m->render(
            $strTemplateHtml,
            $this->_getField(self::FIELD_PARAMS, array())
        );
        $strMessageText = $m->render(
            $strTemplateText,
            $this->_getField(self::FIELD_PARAMS, array())
        );

        $strVhost = Chaplin_Config_Chaplin::getInstance()->getVhost();

        $mail = new Zend_Mail();
        $mail->setFrom('chaplin@'.$strVhost, 'Chaplin');
        $mail->setSubject($this->_getField(self::FIELD_TITLE, null));
        $mail->addTo($modelUser->getEmail());
        $mail->setBodyHtml($strMessageHtml);
        $mail->setBodyText($strMessageText);
        $mail->send();
    }

    abstract public function getType();

    public function getRoutingKey()
    {
        return 'message.'.
        $this->getType().'.'.
        $this->_getField(self::FIELD_USERNAME, null);
    }

    public function getExchangeName()
    {
        return Chaplin_Service_Amqp_Notification::EXCHANGE_NAME;
    }
}
