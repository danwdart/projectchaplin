<?php
class UserController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$strUsername = $this->_request->getParam('id', null);
		if(is_null($strUsername)) {
			return $this->_redirect('/');
		}

		try {
			$modelUser = Chaplin_Gateway::getInstance()
				->getUser()
				->getByUsername($strUsername);
		} catch(Chaplin_Dao_Exception_User_NotFound $e) {
			$this->_redirect('/');
		}

		$this->view->modelUser = $modelUser;
	}
}