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
namespace Chaplin\Module\Api\Form\Channel;

use Zend_Form as Form;
use Zend_Form_Element_File as File;
use Zend_Form_Element_Submit as Submit;
use Zend_Form_Element_Text as Text;
use Zend_Form_Element_Textarea as Textarea;

class Create extends Form
{
    public function init()
    {
        $this->setEncType("multipart/form-data");

        $name = new Text("name");
        $name->setLabel("Name");
        $name->setAttrib("class", "form-control");
        $name->setAttrib("placeholder", "My Cool Channel");

        $description = new Textarea("description");
        $description->setLabel("Description");
        $description->setAttrib("class", "form-control");
        $description->setAttrib("rows", 10);
        $description->setAttrib("placeholder", "Details about my cool channel");

        $header = new File("header");
        $header->setLabel("Upload Header Image");
        $header->setAttrib("class", "form-control");
        $header->setDestination(getenv("UPLOADS_PATH"));

        $create = new Submit('Create');
        $create->setAttrib("class", "btn btn-primary");

        $this->addElements([$name, $description, $header, $create]);

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
                "Create"
            ]
        );
    }
}
