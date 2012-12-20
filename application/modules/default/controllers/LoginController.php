<?php
class LoginController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $form = new default_Form_Login();

        if($this->_request->isPost())
        {
            $post = $this->_request->getPost();

            $username = $post['username'];
            $password = $post['password'];
            
            if(!$form->isValid($post)) {
                return $this->view->assign('form', $form);
            }
            
            if(isset($post['Login']))
            {
                $adapter = new Chaplin_Auth_Adapter_Mongo($username, $password);
                $auth = Chaplin_Auth::getInstance();
                $auth->authenticate($adapter);
                if($auth->hasIdentity()) {
                    $login = new Zend_Session_Namespace('login');
                    if (!is_null($login->url)) {
                        $this->_redirect($login->url);
                        $login->url = null;
                        return;
                    }
                    return $this->_redirect('/');
                }
                else
                {
                    $form->password->addError('Wrong username or password.'.
                        ' Want to try again?');
                    $form->markAsError();
                    return $this->view->assign('form', $form);
                }
            }

            elseif(isset($post['Register']))
            {
                return $this->_redirect('/login/register');
            }

            else
            {
                return $this->view->assign('form', $form->addError('Invalid Action'));
            }
        }

        return $this->view->assign('form', $form);
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

        if($this->_request->isPost())
        {
            $post = $this->_request->getPost();
            
            if(!$form->isValid($post)) {
                return $this->view->assign('form', $form);
            }

            $username = $post['username'];
            $password = $post['password'];
            $password2 = $post['password2'];
            $email = $post['email'];
            $fullname = $post['fullname'];

            // Todo: replace with Zend_Validate
            /*
            if(!User::isValidEmail($email))
            {
                $this->addMessage(array(
                    'text' => 'Not a valid Email',
                    'class' => 'warn'
                ));
            }

            if(!User::isValidUsername($username))
            {
                $this->addMessage(array(
                    'text' => 'Not a Valid Username',
                    'class' => 'warn'
                ));

            }

            if(!User::isValidPassword($password))
            {
                $this->addMessage(array(
                    'text' => 'Not A Valid Password',
                    'class' => 'warn'
                ));
            }

            if(User::exists($username))
            {
                $this->addMessage(array(
                    'text' => 'Username already exists',
                    'class' => 'warn'
                ));
            }*/
            try
            {
                $user = Chaplin_Model_User::create($username, $password);
                $user->setEmail($email);
                $user->setNick($fullname);
                $user->setUserType(new Chaplin_Model_User_Helper_UserType(Chaplin_Model_User_Helper_UserType::ID_USER));
                $user->save();
                       
                // AJAX: Success
                return $this->_redirect($this->_redirect_url);
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
                return $this->view->assign('form', $form->addError('Could not create account. '.
                    'Reason: '.$e->getMessage()));
            }
        }

        return $this->view->assign('form', $form);
    }

    public function userinfoAction()
    {
        if(!Chaplin_Auth::getInstance()->hasIdentity()) {
            return $this->_redirect('/login');
        }
        $form = new default_Form_UserData_Edit();

        $user = Chaplin_Auth::getInstance()->getIdentity()->getUser();

        $form->fullname->setValue($user->getNick());
        $form->email->setValue($user->getEmail());

        if($this->_request->isPost())
        {
            $post = $this->_request->getPost();
            
            if(!$form->isValid($post)) {
                return $this->view->assign('form', $form);
            }
            
            if(isset($post['Save']))
            {
                $oldpassword = $post['oldpassword'];
                $password = $post['password'];
                $password2 = $post['password2'];
                $email = $post['email'];
                $fullname = $post['fullname'];

                if(!$user->verifyPassword($oldpassword))
                {
                    return $this->view->assign('form', $form->addError('Old Password does not match. Want to try again?'));
                }

                /*
                if(!User::isValidEmail($email))
                {
                    $this->addMessage(array(
                        'text' => 'Not a valid Email',
                        'class' => 'warn'
                    ));
                }

                if(!User::isValidUsername($username))
                {
                    $this->addMessage(array(
                        'text' => 'Not a Valid Username',
                        'class' => 'warn'
                    ));

                }

                if(!User::isValidPassword($password))
                {
                    $this->addMessage(array(
                        'text' => 'Not A Valid Password',
                        'class' => 'warn'
                    ));
                }
                */
                try
                {
                    $user->setPassword($password);
                    $user->setEmail($email);
                    $user->setNick($fullname);
                    $user->save();
                    $this->_redirect('/');
                }
                catch(Exception $e)
                {
                    return $this->view->assign('form', $form->addError(
                        'An error occurred whilst saving your details. Please try again.'));
                }
            }
            else
            {
                return $this->view->assign('form', $form->addError('Invalid Request'));
            }
        }

        return $this->view->assign('form', $form);
    }
}
