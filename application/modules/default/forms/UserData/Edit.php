<?php
class default_Form_UserData_Edit extends Zend_Form()
{
    public function init()
    { 
        $this->setMethod('post');
        $this->setAction('/userinfo');

        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('Username:');

        $oldpassword = new Zend_Form_Element_Password('oldpassword');
        $oldpassword->setLabel('Old Password:');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password:');

        $password2 = new Zend_Form_Element_Password('password2');
        $password2->setLabel('Repeat Password:');

        $fullname = new Zend_Form_Element_Text('fullname');
        $fullname->setLabel('Full Name:');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email Address:');

        $register = new Zend_Form_Element_Submit('Save');

        $this->addElements(array($username, $oldpassword, $password, $password2, $fullname, $email, $register));
    }
}
