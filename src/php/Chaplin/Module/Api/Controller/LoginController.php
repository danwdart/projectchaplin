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

use Chaplin\Auth;
use Chaplin\Auth\Adapter\Database as AuthAdapterDB;
use Chaplin\Dao\Exception\User\NotFound as ExceptionUserNotFound;
use Chaplin\Gateway;
use Chaplin\Model\User as ModelUser;
use Chaplin\Model\User\Helper\UserType;
use Chaplin\Module\Api\Form\Auth\Forgot as FormForgot;
use Chaplin\Module\Api\Form\Auth\Login as FormLogin;
use Chaplin\Module\Api\Form\Auth\Validate as FormValidate;
use Chaplin\Module\Api\Form\UserData\Create as FormCreateUser;
use Exception;
use Zend_Controller_Action as Controller;
use Zend_Db_Statement_Exception as StatementException;
use Zend_Http_Client as HttpClient;
use Zend_Json as Json;
use Zend_Oauth_Consumer as OauthConsumer;
use Zend_Oauth_Exception as OauthException;
use Zend_Session_Namespace as SessionNS;

class LoginController extends Controller
{
    private $session;

    public function getIndex()
    {
        $this->view->strTitle = 'Login - Chaplin';
        $form = new FormLogin();

        if ($this->_helper->flashMessenger->hasMessages()) {
            $this->view->messages = $this->_helper->flashMessenger->getMessages();
        }

        $this->view->assign('form', $form);
    }

    public function postIndex()
    {
        $this->view->strTitle = 'Login - Chaplin';
        $form = new FormLogin();

        if ($this->_helper->flashMessenger->hasMessages()) {
            $this->view->messages = $this->_helper->flashMessenger->getMessages();
        }

        $post = $this->_request->getPost();

        $username = $post['username'];
        $password = $post['password'];

        if (isset($post['Register'])) {
            $this->redirect('/login/register');
            return;
        }

        if (isset($post['Forgot'])) {
            $this->redirect('/login/forgot');
            return;
        }

        if (!$form->isValid($post)) {
            $this->view->assign('form', $form);
            return;
        }

        if (!isset($post['Login'])) {
            $form->password->addError('Invalid Action');
            $this->view->assign('form', $form);
            return;
        }

        $adapter = new AuthAdapterDB($username, $password);
        $auth = Auth::getInstance();
        $auth->authenticate($adapter);
        if (!$auth->hasIdentity()) {
            $form->password->addError('Wrong username or password.');
            $form->markAsError();
            return $this->view->assign('form', $form);
        }

        $login = new SessionNS('login');
        if (!is_null($login->url)) {
            $this->redirect($login->url);
            $login->url = null;
            return;
        }

        $this->redirect('/');
    }

    public function getLogout()
    {
        $this->view->layout()->disableLayout();
        $renderer = $this->getHelper('ViewRenderer');
        $renderer->setNoRender(true);

        Auth::getInstance()->clearIdentity();
        $this->redirect($this->redirect_url);
    }

    public function getRegister()
    {
        $this->view->strTitle = 'Register - Chaplin';
        $form = new FormCreateUser();

        $this->view->assign('form', $form);
    }

    public function postRegister()
    {
        $this->view->strTitle = 'Register - Chaplin';
        $form = new FormCreateUser();

        $post = $this->_request->getPost();

        if (!$form->isValid($post)) {
            return $this->view->assign('form', $form);
        }

        $username = $post['username'];
        $password = $post['password'];
        $password2 = $post['password2'];
        $email = $post['email'];
        $fullname = $post['fullname'];
        // TODO validate email
        // TODO validate username
        // TODO validate password
        // TODO check if user exists
        try {
            $user = ModelUser::create($username, $password);
            $user->setEmail($email);
            $user->setNick($fullname);
            $user->setUserType(new UserType(UserType::ID_USER));
            $user->save();

            // AJAX: Success
            $this->redirect($this->redirect_url);
            return;
        } catch (StatementException $e) {
            $form->username->addError('Could not create account - a user aleady exists with that name');
            $form->markAsError();
            $this->view->assign('form', $form);
        } catch (Exception $e) {
            $form->username->addError('Could not create account. Reason: '.$e->getMessage());
            $form->markAsError();
            $this->view->assign('form', $form);
        }
    }

    public function getForgot()
    {
        $this->view->strTitle = 'Forgot - Chaplin';

        $form = new FormForgot();

        $this->view->form = $form;
    }

    public function postForgot()
    {
        $this->view->strTitle = 'Forgot - Chaplin';

        $form = new FormForgot();

        if (!$form->isValid($this->_request->getPost())) {
            return $this->view->form = $form;
        }

        try {
            $modelUser = Gateway::getUser()
                ->getByUsername($form->username->getValue());

            Gateway::getEmail()
                ->resetPassword($modelUser);
        } catch (ExceptionUserNotFound $e) {
        }

        $this->_helper->flashMessenger(
            'You should soon receive an email containing<br/>'.
            'instructions on how to set your password.'
        );
        $this->redirect('/login');
    }

    public function getValidate()
    {
        $this->view->strTitle = 'Validate - Chaplin';
        $strToken = $this->_request->getParam('token', null);
        if (empty($strToken)) {
            $this->redirect('/login');
        }

        $form = new FormValidate($strToken);

        if (!$this->_request->isPost()) {
            return $this->view->form = $form;
        }

        if (!$form->isValid($this->_request->getPost())) {
            return $this->view->form = $form;
        }
    }

    public function postValidate()
    {
        $this->view->strTitle = 'Validate - Chaplin';

        $strToken = $this->_request->getParam('token', null);

        if (empty($strToken)) {
            $this->redirect('/login');
        }

        $form = new FormValidate($strToken);

        Gateway::getUser()
            ->updateByToken(
                $strToken,
                $form->password->getValue()
            );

        $this->_helper->flashMessenger(
            'If a user account exists then your password has been set.<br/>'.
            'You can now login below.'
        );
        $this->redirect('/login');
    }
}
