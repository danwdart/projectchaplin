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
class default_Form_Video_Comment extends Zend_Form
{
    public function init()
    {
        $this->setAction('');
        $this->setMethod('post');
        $this->setAttribs(
            array(
            'class' => 'ajax',
            'rel' => 'comments'
            )
        );
        
        $comment = new Zend_Form_Element_Textarea('Comment');
        $comment->setAttribs(
            array(
            'style' => 'width:250px;height:40px;margin:0;',
            'placeholder' => 'Your Comment'
            )
        );
        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setLabel('Say it!');
        
        $this->addElements(array($comment, $submit));
    }
}
        
        
