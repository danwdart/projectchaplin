<?php
use Chaplin\Model\User as User;
class LoginController extends Chaplin_Controller_Action_App
{
    private $_redirect_url;

    public function preDispatch()
    {
        parent::preDispatch();

        $this->setAppTitle('Login');

        $this->_redirect_url = $this->getReferrer();

        if(strstr($this->_redirect_url, '/login') !== false)
        {
            $this->_redirect_url = '/';
        }
    }

    public function indexAction()
    {
        $form = $this->_getLoginForm();

        if($this->isPost())
        {
            $post = $this->getPost();

            $username = $post['username'];
            $password = $post['password'];

            if(isset($post['Login']))
            {
                $adapter = new Chaplin_Auth_Adapter_Mongo($username, $password);
                $auth = Chaplin_Auth::getInstance();
                $auth->authenticate($adapter);
                if($auth->hasIdentity()) {
                    $this->_redirect($this->_redirect_url);
                }
                else
                {
                    $this->addMessage(array(
                        'text' => 'Wrong username or password. Want to try again?',
                        'class' => 'warn'
                    ));
                }
            }

            elseif(isset($post['Register']))
            {
                $this->_redirect('/login/register');
            }

            else
            {
                $this->addMessage(array(
                    'text' => 'Invalid action',
                    'class' => 'error'
                ));
            }
        }

        $this->view->assign('form', $form);
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
        $this->setAppTitle('Register a new user');

        $form = $this->_getUserDataForm();

        if($this->isPost())
        {
            $post = $this->getPost();

            if(isset($post['Register']))
            {
                $username = $post['username'];
                $password = $post['password'];
                $password2 = $post['password2'];
                $email = $post['email'];
                $fullname = $post['fullname'];

                if($password != $password2)
                {
                    $this->addMessage(array(
                        'text' => 'Passwords must match',
                        'class' => 'warn'
                    ));
                }
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
                }
                 */
                
                if(is_null($this->getMessages()))
                {
                    try
                    {
                        $user = Chaplin_Model_User::create($username, $password);;
                        $user->setEmail($email);
                        $user->setNick($fullname);
                        $user->setUserType(new Chaplin_Model_User_Helper_UserType(Chaplin_Model_User_Helper_UserType::ID_USER));
                        $user->save();
    
                        $this->addMessage(array(
                            'text' => 'Created Account',
                            'class' => 'warn',
                            'redirect' => '/login'
                        ));
                    }
                    catch(Exception $e)
                    {
                        $this->addMessage(array(
                            'text' => 'Could not create account. Reason: '.$e->getMessage(),
                            'class' => 'error'
                        ));
                    }
                }
            }
            else
            {
                $this->addMessage(array(
                    'text' => 'Invalid Request',
                    'class' => 'error'
                ));
            }
        }

        $this->view->assign('form', $form);
    }

    public function userinfoAction()
    {
        $this->requireLogin();

        $this->setAppTitle('Edit User Info');

        $form = $this->_getUserDataForm(true);

        $user = Chaplin_Auth::getInstance()->getIdentity()->getUser();

        $form->fullname->setValue($user->getNick());
        $form->email->setValue($user->getEmail());

        if($this->isPost())
        {
            $post = $this->getPost();

            if(isset($post['Save']))
            {
                $oldpassword = $post['oldpassword'];
                $password = $post['password'];
                $password2 = $post['password2'];
                $email = $post['email'];
                $fullname = $post['fullname'];

                if(!$user->isPassword($oldpassword))
                {
                    $this->addMessage(array(
                        'text' => 'Old Password does not match. Want to try again?',
                        'class' => 'error'
                    ));
                    return null; // Don't go any further
                }

                if($password != $password2)
                {
                    $this->addMessage(array(
                        'text' => 'Passwords must match',
                        'class' => 'warn'
                    ));
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
                if(is_null($this->getMessages()))
                {
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
                        $this->addMessage(array(
                            'text' => 'An error occurred whilst saving your details. Please try again.',
                            'class' => 'error'
                        ));
                    }
                }
            }
            else
            {
                $this->addMessage(array(
                    'text' => 'Invalid Request',
                    'class' => 'error'
                ));
            }
        }

        $this->view->assign('form', $form);
    }

    private function _getLoginForm()
    {
        $form = new Zend_Form();
        $form->setMethod('post');
        $form->setAction('/login?redirect=' . $this->_redirect_url);

        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Username:');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password:');

        $submit = new Zend_Form_Element_Submit('Login');

        $register = new Zend_Form_Element_Submit('Register');

//      $forgot = new Zend_Form_Element_Submit('Forgot');
//      $forgot->setLabel('Forgot Password');

        $form->addElements(array($username, $password, $submit, $register));

        return $form;
    }    

    private function _getUserDataForm($edit = false)
    {
        $form = new Zend_Form();
        $form->setMethod('post');
        $form->setAction('/login/register');
        if($edit)
        {
            $form->setAction('/userinfo');
        }

        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Username:');

        if($edit)
        {
            $oldpassword = new Zend_Form_Element_Password('oldpassword');
            $oldpassword->setLabel('Old Password:');
        }

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password:');

        $password2 = new Zend_Form_Element_Password('password2');
        $password2->setLabel('Repeat Password:');

        $fullname = new Zend_Form_Element_Text('fullname');
        $fullname->setLabel('Full Name:');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email Address:');

        $register = new Zend_Form_Element_Submit('Register');
        if($edit)
        {
            $register = new Zend_Form_Element_Submit('Save');
        }

        $form->addElement($username);

        if($edit)
        {
            $form->addElement($oldpassword);
        }

        $form->addElements(array($password, $password2, $fullname, $email, $register));

        return $form;
    } 
}
