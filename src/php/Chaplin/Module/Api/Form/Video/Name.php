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

use Zend_Form as Form;
use Zend_Form_Element_Submit as Submit;
use Zend_Form_SubForm as ZendSubForm;
use Chaplin\Iterator\IteratorInterface as Itt;

class Name extends Form
{
    private $ittVideos;

    public function __construct(Itt $ittVideos)
    {
        $this->ittVideos = $ittVideos;
        parent::__construct();
    }

    public function init()
    {
        $sfVideos = new ZendSubForm('Videos');
        foreach ($this->ittVideos as $modelVideo) {
            $subform = new SubForm($modelVideo);
            $sfVideos->addSubForm($subform, $modelVideo->getId());
        }

        $submit = new Submit('Save');

        $this->addSubForm($sfVideos, 'Videos');
        $this->addElement($submit);
    }
}
