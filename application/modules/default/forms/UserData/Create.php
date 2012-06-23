<?php
class default_Form_UserData_Create extends Zend_Form()
{
    public function init()
    { 
        $this->setMethod('post');
        $this->setAction('/login/register');

        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Username:');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password:');

        $password2 = new Zend_Form_Element_Password('password2');
        $password2->setLabel('Repeat Password:');

        $fullname = new Zend_Form_Element_Text('fullname');
        $fullname->setLabel('Full Name:');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email Address:');

        $register = new Zend_Form_Element_Submit('Register');

        $this->addElements(array($username, $password, $password2, $fullname, $email, $register));
    }
}
