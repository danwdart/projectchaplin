<?php
use Chaplin\Model\User as User;
class LoginController extends Zend_Controller_Action
{
    private $_redirect_url;

    public function preDispatch()
    {
        parent::preDispatch();
        
        $this->_redirect_url = $this->_request->getServer('HTTP_REFERER');

        if(strstr($this->_redirect_url, '/login') !== false)
        {
            $this->_redirect_url = '/';
        }
    }

    public function indexAction()
    {
        $form = new default_Form_Login();

        if($this->_request->isPost())
        {
            $post = $this->_request->getPost();

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

        $form = new default_Form_UserData_Create();

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
        $form = new default_Form_UserData_Edit();

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
}
