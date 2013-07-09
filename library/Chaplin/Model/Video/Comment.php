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
class Chaplin_Model_Video_Comment
    extends Chaplin_Model_Field_Hash
{
    const FIELD_COMMENTID = 'CommentId';
    const FIELD_VIDEOID = 'VideoId';
    const FIELD_USERNAME = 'Username';
    const FIELD_COMMENT = 'Comment';
    //Feedback
    
    protected $_arrFields = array(
        self::FIELD_ID => array('Class' => 'Chaplin_Model_Field_FieldId'),
        self::FIELD_VIDEOID => array('Class' => 'Chaplin_Model_Field_Field'),
        self::FIELD_USERNAME => array('Class' => 'Chaplin_Model_Field_Field'),
        self::FIELD_COMMENT => array('Class' => 'Chaplin_Model_Field_Field')
    );
    
    public static function create(
        Chaplin_Model_Video $modelVideo,
        Chaplin_Model_User $modelUser,
        $strComment
    ) {
        $comment = new self();
        $comment->_setField(self::FIELD_ID, md5(uniqid()));
        $comment->_setField(self::FIELD_VIDEOID, $modelVideo->getVideoId());
        $comment->_setField(self::FIELD_USERNAME, $modelUser->getUsername());
        $comment->_setField(self::FIELD_COMMENT, $strComment);
        return $comment;
    }

    public function getCommentId()
    {
        return $this->_getField(self::FIELD_ID, null);
    }
    
    public function getUsername()
    {
        return $this->_getField(self::FIELD_USERNAME, null);
    }
    
    private $_modelUser;
    public function getUser()
    {
        if(is_null($this->_modelUser)) {
            $this->_modelUser = Chaplin_Gateway::getInstance()
                ->getUser()
                ->getByUsername($this->getUsername());
        }
        return $this->_modelUser;
    }
    
    public function getComment()
    {
        return $this->_getField(self::FIELD_COMMENT, null);
    }

    public function isMine()
    {
        if(!Chaplin_Auth::getInstance()->hasIdentity()) {
            return false;
        }
        if(Chaplin_Auth::getInstance()->getIdentity()->getUser()->isGod()) {
            // God users own everything, mwuhahaha
            return true;
        }
        return Chaplin_Auth::getInstance()->getIdentity()->getUser()->getUsername() ==
            $this->getUsername();
    }
}
