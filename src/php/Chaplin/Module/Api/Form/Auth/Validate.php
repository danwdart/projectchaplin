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
use Zend_Form_Element_Hidden as Hidden;
use Zend_Form_Element_Password as Password;
use Zend_Form_Element_Submit as Submit;
use Zend_Validate_StringLength as ValidateStringLength;
use Zend_Validate_Identical as ValidateIdentical;

class Validate extends Form
{
    private $_strToken;

    public function __construct($strToken)
    {
        parent::__construct();
        $this->_strToken = $strToken;
    }

    public function init()
    {
        $this->setMethod('post');

        $token = new Hidden('token');
        $token->setValue($this->_strToken);

        $password = new Password('password');
        $password->setLabel('New Password:');

        $password2 = new Password('password2');
        $password2->addValidators(
            array(
            new ValidateStringLength(
                array(
                'min' => 6
                )
            ),
            new ValidateIdentical(
                array(
                'token' => 'password'
                )
            )
            )
        );

        $password2->setLabel('Confirm Password:');

        $submit = new Submit('Reset');

        $this->addElements(array($token, $password, $password2, $submit));
    }
}
