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

        $username = new Text('username');
        $username->setLabel("Username");
        $username->setAttrib('placeholder', 'johnsmith');
        $username->setAttrib('required', 'true');
        $username->setAttrib("class", "form-control");

        $password = new Password('password');
        $password->setLabel("Password");
        $password->setAttrib('placeholder', 'password1');
        $password->setAttrib('required', 'true');
        $password->setAttrib("class", "form-control");

        $submit = new Submit('Login');
        $submit->setAttrib("class", "btn btn-primary");
        $submit->removeDecorator('DtDdWrapper');

        $this->addElements([$username, $password, $submit]);

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
                "Login"
            ]
        );
    }
}
