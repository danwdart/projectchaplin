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
 * @link      https://github.com/danwdart/projectchaplin
**/
namespace Chaplin\Module\Api\Controller;

use Chaplin_Auth as Auth;
use Chaplin_Controller_Action_Api as ApiController;
use Chaplin_Dao_Exception_User_NotFound as ExceptionUserNotFound;
use Chaplin_Gateway as Gateway;
use Chaplin\Module\Api\Form\UserData\Edit as FormEditUserData;
use Chaplin_Service as Service;
use Exception;

class UserController extends ApiController
{
    public function indexAction()
    {
        $strUsername = $this->_request->getParam('id', null);
        if(is_null($strUsername)) {
            $this->_redirect('/');
            return;
        }

        try {
            $modelUser = Gateway::getInstance()
             ->getUser()
             ->getByUsername($strUsername);
        } catch(ExceptionUserNotFound $e) {
            $this->_redirect('/');
        }

        if ($this->_isAPICall()) {
            return $this->view->assign($modelUser->toArray());
        }

        $this->view->bIsMe = Auth::getInstance()->hasIdentity() &&
         Auth::getInstance()->getIdentity()->getUser()->getUsername() ==
          $modelUser->getUsername();

        $this->view->modelUser = $modelUser;

        if (!$this->view->bIsMe) {
            return;
        }

        $this->view->strTitle = $modelUser->getNick().' - Chaplin';

        $form = new FormEditUserData();

        $user = Auth::getInstance()->getIdentity()->getUser();

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

        if(!isset($post['Update'])) {
            $form->Update->addError('Invalid Request');
            return $this->view->assign('form', $form);
        }

        $oldpassword = $post['oldpassword'];
        $password = $post['password'];
        $password2 = $post['password2'];
        $email = $post['email'];
        $fullname = $post['fullname'];

        if(!$user->verifyPassword($oldpassword)) {
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
        $strUsername = $this->_request->getParam('id', null);
        $serviceYouTube = Service::getInstance()->getYouTube();
        $this->view->ittVideos = $serviceYouTube->getUserUploads($strUsername, $strPageToken);

        if ($strPageToken) {
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer('youtube-partial');
        } else {
            if ($this->view->ittVideos->count()) {
                $this->view->strTitle = $this->view->ittVideos->items[0]->getSnippet()->channelTitle.
                ' from YouTube - Chaplin';
            }
        }
    }

    public function vimeoAction()
    {
        $strPage = $this->_request->getQuery('page', 1);
        $intPage = intval($strPage);

        $strUsername = $this->_request->getParam('id', null);
        $serviceVimeo = Service::getInstance()->getVimeo();

        $this->view->ittVideos = $serviceVimeo->getUserUploads($strUsername, $intPage);
        if (1 < $strPage) {
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer('vimeo-partial');
        } else {
            $this->view->strTitle = $this->view->ittVideos['data'][0]['user']['name'].
                    ' from Vimeo - Chaplin';
        }
    }
}
