<?php
class default_Form_Login extends Zend_Form
{
    public function init()
    {
    
        $this->setMethod('post');
        $this->setAction('/login?redirect=' . $this->_redirect_url);

        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Username:');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password:');

        $submit = new Zend_Form_Element_Submit('Login');
        $register = new Zend_Form_Element_Submit('Register');

//      $forgot = new Zend_Form_Element_Submit('Forgot');
//      $forgot->setLabel('Forgot Password');

        $this->addElements(array($username, $password, $submit, $register));

    }
}
