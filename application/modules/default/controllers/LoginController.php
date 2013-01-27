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
class LoginController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $form = new default_Form_Login();

        if(!$this->_request->isPost()) {
            return $this->view->assign('form', $form);
        }

        $post = $this->_request->getPost();

        $username = $post['username'];
        $password = $post['password'];

        if(isset($post['Register'])) {
            return $this->_redirect('/login/register');
        }
        
        if(!$form->isValid($post)) {
            return $this->view->assign('form', $form);
        }
        
        if(!isset($post['Login'])) {
            $form->password->addError('Invalid Action');
            return $this->view->assign('form', $form);
        }
            
        $adapter = new Chaplin_Auth_Adapter_Database($username, $password);
        $auth = Chaplin_Auth::getInstance();
        $auth->authenticate($adapter);
        if(!$auth->hasIdentity()) {
            $form->password->addError('Wrong username or password. Want to try again?');
            $form->markAsError();
            return $this->view->assign('form', $form);
        }

        $login = new Zend_Session_Namespace('login');
        if (!is_null($login->url)) {
            $this->_redirect($login->url);
            $login->url = null;
            return;
        }

        return $this->_redirect('/');
    }

    public function logoutAction()
    {
        $this->view->layout()->disableLayout();
        $renderer = $this->getHelper('ViewRenderer');
        $renderer->setNoRender(true);

        Chaplin_Auth::getInstance()->clearIdentity();
        $this->_redirect($this->_redirect_url);
    }

    public function registerAction()
    {
        $form = new default_Form_UserData_Create();

        if(!$this->_request->isPost()) {
            return $this->view->assign('form', $form);
        }

        $post = $this->_request->getPost();
        
        if(!$form->isValid($post)) {
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
        try
        {
            $user = Chaplin_Model_User::create($username, $password);
            $user->setEmail($email);
            $user->setNick($fullname);
            $user->setUserType(
                new Chaplin_Model_User_Helper_UserType(
                    Chaplin_Model_User_Helper_UserType::ID_USER
                )
            );
            $user->save();
                   
            // AJAX: Success
            return $this->_redirect($this->_redirect_url);
        }
        catch(Exception $e)
        {
            $form->Register->addError('Could not create account. Reason: '.$e->getMessage());
            return $this->view->assign('form', $form);
        }
    }

    public function userinfoAction()
    {
        if(!Chaplin_Auth::getInstance()->hasIdentity()) {
            return $this->_redirect('/login');
        }
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
            $form->oldpassword->addError('Old Password does not match. Want to try again?');
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
}
