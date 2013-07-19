<?php
class Chaplin_Gateway_Email
	extends Chaplin_Gateway_Abstract
{
	private $_daoExchange;

	public function __construct(Chaplin_Dao_Smtp_Exchange $daoExchange)
	{
		$this->_daoExchange = $daoExchange;
	}

	public function email(
		Chaplin_Model_User $modelUser,
		$strSubject,
		$strTemplate,
		$arrParams
	) {
		$this->_daoExchange->email($modelUser, $strSubject, $strTemplate, $arrParams);
	}

	public function videoFinished(
		Chaplin_Model_Video $modelVideo
	) {
		$strUsername = $modelVideo->getUsername();
		$modelUser = Chaplin_Gateway::getUser()->getByUsername($strUsername);

		$this->_daoExchange->email(
			$modelUser,
			'Video Finished Processing',
			'videofinished',
			[
				'Nick' => $modelUser->getNick(),
				'Name' => $modelVideo->getTitle()
			]
		);
	}

	public function resetPassword(
		Chaplin_Model_User $modelUser
	) {
		$strVhost = Chaplin_Config_Servers::getInstance()->getVhost();

		$strValidationToken = $modelUser->resetPassword();
		// Let's make sure that we save before we send the email.
		// It's a bit odd but it ensures atomic saves.
        Chaplin_Gateway::getUser()->save($modelUser);

        $this->_daoExchange->email(
			$modelUser,
			'Reset Password',
			'resetpassword',
			[
				'Url' => 'http://'.$strVhost.'/login/validate/token/'.$strValidationToken
			]
		);
	}
}