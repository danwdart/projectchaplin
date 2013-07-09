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
class default_Form_Video_SubForm extends Zend_Form_SubForm
{
    private $_modelVideo;

    public function __construct(Chaplin_Model_Video $modelVideo)
    {
        $this->_modelVideo = $modelVideo;
        
        parent::__construct();
    }

    public function init()
    {
        $modelVideo = $this->_modelVideo;
    
        $strImageURL = $modelVideo->getThumbnail();
        $elImage = new Zend_Form_Element_Image('Image');
        $elImage->setImage($strImageURL);
        $elImage->setAttribs(array('style' => 'max-width: 200px; height: 150px;'));
        $elTitle = new Zend_Form_Element_Text('Title');
        $elTitle->setLabel('Title');
        $elTitle->setValue($modelVideo->getSuggestedTitle());
        $elTitle->addValidators(array('NotEmpty'));
        $elDescription = new Zend_Form_Element_Textarea('Description');
        $elDescription->setAttribs(array('style' => 'width: 200px; height:75px;'));
        $elDescription->setLabel('Description');
        $elDescription->addValidators(array('NotEmpty'));
        $elLicence = new Zend_Form_Element_Select('Licence');
        $elLicence->setMultiOptions(Chaplin_Model_Video_Licence::getSelectOptions());
        $elLicence->setValue($modelVideo->getLicenceId());
        $elLicence->setLabel('Licence');
        $this->addElements(array($elImage, $elTitle, $elDescription, $elLicence));
        $this->setAttribs(array('style' => 'float:left; width: 240px; padding: 5px; border: 0; margin: 5px'));
        $this->removeDecorator('DtDdWrapper');
    }
}
