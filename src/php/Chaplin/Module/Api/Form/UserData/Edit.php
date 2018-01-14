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
 * @link      https://github.com/kathiedart/projectchaplin
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
        $username->setAttrib('placeholder', 'Username');
        $username->removeDecorator('Label');

        $oldpassword = new Password('oldpassword');
        $oldpassword->setAttrib('placeholder', 'Old password');
        $oldpassword->removeDecorator('Label');

        $password = new Password('password');
        $password->setAttrib('placeholder', 'New password');
        $password->removeDecorator('Label');

        $password2 = new Password('password2');
        $password2->setAttrib('placeholder', 'New password (again)');
        $password2->removeDecorator('Label');

        $fullname = new Text('fullname');
        $fullname->setAttrib('placeholder', 'Full name');
        $fullname->removeDecorator('Label');

        $email = new Text('email');
        $email->setAttrib('placeholder', 'Email address');
        $email->removeDecorator('Label');

        $register = new Submit('Update');
        $register->removeDecorator('DtDdWrapper');

        $this->addElements(array($username, $oldpassword, $password, $password2, $fullname, $email, $register));
    }
}
