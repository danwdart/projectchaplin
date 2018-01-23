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

use Chaplin\Model\Video as ModelVideo;
use Chaplin\Model\Video\Licence as VideoLicence;
use Chaplin\Model\Video\Privacy as VideoPrivacy;
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

    public function __construct(ModelVideo $modelVideo)
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
        $elTitle->setAttrib("class", "form-control");
        $elTitle->setLabel('Title');
        $elTitle->setValue($modelVideo->getSuggestedTitle());
        $elTitle->addValidators(['NotEmpty']);

        $elDescription = new Textarea('Description');
        $elDescription->setAttrib("class", "form-control");
        $elDescription->setAttrib("rows", 3);
        $elDescription->setLabel('Description');
        $elDescription->addValidators(['NotEmpty']);

        $elLicence = new Select('Licence');
        $elLicence->setAttrib("class", "form-control");
        $elLicence->setMultiOptions(VideoLicence::getSelectOptions());
        $elLicence->setValue($modelVideo->getLicenceId());
        $elLicence->setLabel('Licence');

        $elPrivacy = new Select('Privacy');
        $elPrivacy->setAttrib("class", "form-control");
        $elPrivacy->setLabel('Limit To');
        $elPrivacy->setValue($modelVideo->getPrivacyId());
        $elPrivacy->addValidators(['NotEmpty']);
        $elPrivacy->setMultiOptions(VideoPrivacy::getSelectOptions());

        $this->addElements([$elImage, $elTitle, $elDescription, $elLicence, $elPrivacy]);

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
                "Save"
            ]
        );
    }
}
