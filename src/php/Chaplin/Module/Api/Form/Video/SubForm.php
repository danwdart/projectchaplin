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
namespace Chaplin\Module\Api\Form\Video;

use Chaplin_Model_Video_Licence as VideoLicence;
use Chaplin_Model_Video_Privacy as VideoPrivacy;
use Zend_Form as Form;
use Zend_Form_Element_Image as Image;
use Zend_Form_Element_Select as Select;
use Zend_Form_Element_Submit as Submit;
use Zend_Form_Element_Text as Text;
use Zend_Form_Element_Textarea as Textarea;
use Zend_Form_SubForm as ZendSubForm;

class SubForm extends ZendSubForm
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
        $elImage = new Image('Image');
        $elImage->setImage($strImageURL);
        $elImage->setAttribs(['style' => 'max-width: 200px; height: 150px;']);
        $elTitle = new Text('Title');
        $elTitle->setLabel('Title');
        $elTitle->setValue($modelVideo->getSuggestedTitle());
        $elTitle->addValidators(['NotEmpty']);
        $elDescription = new Textarea('Description');
        $elDescription->setAttribs(['style' => 'width: 200px; height:75px;']);
        $elDescription->setLabel('Description');
        $elDescription->addValidators(['NotEmpty']);
        $elLicence = new Select('Licence');
        $elLicence->setMultiOptions(VideoLicence::getSelectOptions());
        $elLicence->setValue($modelVideo->getLicenceId());
        $elLicence->setLabel('Licence');
        $elPrivacy = new Select('Privacy');
        $elPrivacy->setLabel('Limit To');
        $elPrivacy->setValue($modelVideo->getPrivacyId());
        $elPrivacy->addValidators(['NotEmpty']);
        $elPrivacy->setMultiOptions(VideoPrivacy::getSelectOptions());
        $this->addElements([$elImage, $elTitle, $elDescription, $elLicence, $elPrivacy]);
        $this->setAttribs(['style' => 'float:left; width: 240px; padding: 5px; border: 0; margin: 5px']);
        $this->removeDecorator('DtDdWrapper');
    }
}
