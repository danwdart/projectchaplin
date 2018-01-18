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
namespace Chaplin\Module\Api\Form\UserData;

use Zend_Form as Form;
use Zend_Form_Element_Password as Password;
use Zend_Form_Element_Submit as Submit;
use Zend_Form_Element_Text as Text;

class Edit extends Form
{
    public function init()
    {
        $this->setMethod('post');

        $username = new Text('username');
        $username->setLabel("Username");
        $username->setAttrib("class", "form-control");
        $username->setAttrib('placeholder', 'johnsmith');

        $oldpassword = new Password('oldpassword');
        $oldpassword->setLabel("Old Password");
        $oldpassword->setAttrib("class", "form-control");
        $oldpassword->setAttrib('placeholder', 'abcdefg');

        $password = new Password('password');
        $password->setLabel("New Password");
        $password->setAttrib("class", "form-control");
        $password->setAttrib('placeholder', 'abcdefgh');

        $password2 = new Password('password2');
        $password2->setLabel("New Password (again)");
        $password2->setAttrib("class", "form-control");
        $password2->setAttrib('placeholder', 'abcdefgh');

        $fullname = new Text('fullname');
        $fullname->setLabel("Full Name");
        $fullname->setAttrib("class", "form-control");
        $fullname->setAttrib('placeholder', 'John Smith');

        $email = new Text('email');
        $email->setAttrib("type", "email");
        $email->setLabel("Email Address");
        $email->setAttrib("class", "form-control");
        $email->setAttrib('placeholder', 'john@smith.com');

        $register = new Submit('Update');
        $register->setAttrib("class", "btn btn-primary");


        $this->addElements(array($username, $oldpassword, $password, $password2, $fullname, $email, $register));

        $this->setDecorators(
            [
                'FormElements',
                'Form'
            ]
        );

        $this->setElementDecorators(
            [
                'ViewHelper',
                'Label',
                ['HtmlTag', ['tag' => 'div', 'class' => 'form-group']]
            ]
        );

        $this->setElementDecorators(
            [
                'ViewHelper'
            ],
            [
                "Update"
            ]
        );
    }
}
