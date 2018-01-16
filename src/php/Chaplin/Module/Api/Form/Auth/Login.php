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
namespace Chaplin\Module\Api\Form\Auth;

use Chaplin\Form\Element\Html;
use Zend_Form as Form;
use Zend_Form_Element_Password as Password;
use Zend_Form_Element_Submit as Submit;
use Zend_Form_Element_Text as Text;

class Login extends Form
{
    public function init()
    {

        $this->setMethod('post');
        $this->setAction('/login?redirect=' . $this->_redirect_url);

        $face = new Html('icon');
        $face->setValue('<div class="face"></div>');
        $face->removeDecorator('Label');
        $this->addElement($face);

        $username = new Text('username');
        $username->setAttrib('placeholder', 'Username');
        $username->removeDecorator('Label');
        $username->setAttrib('required', 'true');

        $password = new Password('password');
        $password->setAttrib('placeholder', 'Password');
        $password->removeDecorator('Label');
        $password->setAttrib('required', 'true');

        $submit = new Submit('Login');

        $submit->removeDecorator('DtDdWrapper');

        $this->addElements(array($username, $password));

        $tag = new Html('forgotPassword');
        $tag->setValue('<a href="/login/forgot">I don\'t know my password</a>');
        $tag->removeDecorator('Label');
        $this->addElement($tag);

        $this->addElement($submit);

        $tag2 = new Html('register');
        $tag2->setValue('<a href="/login/register">No account? Register here</a>');
        $tag2->removeDecorator('Label');
        $this->addElement($tag2);

    }
}
