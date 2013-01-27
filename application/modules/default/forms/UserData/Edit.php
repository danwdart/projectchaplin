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
class default_Form_UserData_Edit extends Zend_Form
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
