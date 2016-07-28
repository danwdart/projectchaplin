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
class UserController extends Chaplin_Controller_Action_Api
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

        if ($this->_isAPICall()) {
            return $this->view->assign($modelUser->toArray());
        }

		$this->view->bIsMe = Chaplin_Auth::getInstance()->hasIdentity() &&
			Chaplin_Auth::getInstance()->getIdentity()->getUser()->getUsername() ==
				$modelUser->getUsername();

		$this->view->modelUser = $modelUser;

		if (!$this->view->bIsMe) {
			return;
		}

        $this->view->strTitle = $modelUser->getNick().' - Chaplin';

        $form = new default_Form_UserData_Edit();

        $user = Chaplin_Auth::getInstance()->getIdentity()->getUser();

        $form->username->setValue($user->getUsername());
        $form->fullname->setValue($user->getNick());
        $form->email->setValue($user->getEmail());

        if(!$this->_request->isPost()) {
            return $this->view->assign('form', $form);
        }

        $post = $this->_request->getPost();

        if(!$form->isValid($post)) {
            return $this->view->assign('form', $form);
        }

        if(!isset($post['Save'])) {
            $form->Save->addError('Invalid Request');
            return $this->view->assign('form', $form);
        }

        $oldpassword = $post['oldpassword'];
        $password = $post['password'];
        $password2 = $post['password2'];
        $email = $post['email'];
        $fullname = $post['fullname'];

        if(!$user->verifyPassword($oldpassword))
        {
            $form->oldpassword->addError('Incorrect old password.');
            return $this->view->assign('form', $form);
        }
        // @TODO add valid email
        // @TODO add valid Username
        // @TODO add valid password
        try
        {
            if (!empty($password) && !empty($password2) && $password == $password2) {
                $user->setPassword($password);
            }
            $user->setEmail($email);
            $user->setNick($fullname);
            $user->save();
            $this->_redirect('/');
        }
        catch(Exception $e)
        {
            $form->Save->addError('An error occurred whilst saving your details. Please try again.');
            return $this->view->assign('form', $form);
        }
	}

	public function youtubeAction()
	{
        $strPageToken = $this->_request->getQuery('pageToken', null);
        if ($strPageToken) {
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer('youtube-partial');
        }

		$strUsername = $this->_request->getParam('id', null);

        $serviceYouTube = Chaplin_Service::getInstance()->getYouTube();

		$this->view->ittVideos = $serviceYouTube->getUserUploads($strUsername, $strPageToken);

        $this->view->strTitle = $this->view->ittVideos->items[0]->getSnippet()->channelTitle.
            ' from YouTube - Chaplin';
	}

    public function vimeoAction()
	{
		$strUsername = $this->_request->getParam('id', null);

        $serviceVimeo = Chaplin_Service::getInstance()->getVimeo();

		$this->view->ittVideos = $serviceVimeo->getUserUploads($strUsername);

        $this->view->strTitle = $this->view->ittVideos['data'][0]['user']['name'].
            ' from Vimeo - Chaplin';
	}
}
