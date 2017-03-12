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
 * @author    Kathie Dart <chaplin@kathiedart.uk>
 * @copyright 2012-2017 Project Chaplin
 * @license   http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version   GIT: $Id$
 * @link      https://github.com/kathiedart/projectchaplin
**/
class Admin_Form_Import_Files extends Zend_Form
{
    private $_iterator;

    public function __construct(Iterator $iterator)
    {
        $this->_iterator = $iterator;
        parent::__construct();
    }

    public function init()
    {
        $this->setAttribs(
            array(
            'class' => 'fileimport'
            )
        );
        $this->setAction('/admin/import/convert');
        // Until we set this into a session or cookie...
        $subform = new Zend_Form_SubForm('Videos');
        $subform->setLegend('Please choose videos to upload...');
        foreach($this->_iterator as $file) {
            $checkbox = new Zend_Form_Element_Checkbox(base64_encode($file->getPathName()));
            $checkbox->setLabel($file->getBaseName('.'.$file->getExtension()));
            $checkbox->setDecorators(array('ViewHelper','Label','HtmlTag'));
            $subform->addElement($checkbox);                
        }
        $submit = new Zend_Form_Element_Submit('Upload');

        $this->addSubForm($subform, 'Videos');
        $this->addElement($submit);
    }
}