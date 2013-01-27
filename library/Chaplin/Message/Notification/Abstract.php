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
abstract class Chaplin_Message_Notification_Abstract
	extends Chaplin_Message_Abstract
{
	const FIELD_TITLE = 'Title';
	const FIELD_USERNAME = 'Username';
	const FIELD_USERTYPEID = 'UserTypeId';
	const FIELD_MAILTEMPLATE = 'MailTemplate';
	const FIELD_PARAMS = 'Params';

    public function process()
    {
    	$strUsername = $this->_getField(
    		self::FIELD_USERNAME,
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

    	if(is_null($modelUser->getEmail())) {
    		return;
    	}

    	$strPathToTemplateHtml = APPLICATION_PATH.'/../mustache/en_GB/mail/html/'.
    		$this->_getField(self::FIELD_MAILTEMPLATE, null).'.mustache';
    	$strPathToTemplateText = APPLICATION_PATH.'/../mustache/en_GB/mail/text/'.
    		$this->_getField(self::FIELD_MAILTEMPLATE, null).'.mustache';

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

		$mail = new Zend_Mail();
		$mail->setFrom('chaplin@dandart.co.uk', 'Chaplin');
		$mail->setSubject($this->_getField(self::FIELD_TITLE, null));
		$mail->addTo($modelUser->getEmail());
		$mail->setBodyHtml($strMessageHtml);
		$mail->setBodyText($strMessageText);
		$mail->send();
    }

    abstract public function getType();
    
    public function getRoutingKey()
    {
        return 'notification.'.
        	$this->getType().'.'.
        	$this->_getField(self::FIELD_USERNAME, null).'.'.
        	$this->_getField(self::FIELD_USERTYPEID, null);
    }

    public function getExchangeName()
    {
        return Chaplin_Service_Amqp_Notification::EXCHANGE_NAME;
    }
}    
