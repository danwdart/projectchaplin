<?php
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
