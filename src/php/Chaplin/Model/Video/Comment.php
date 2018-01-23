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

namespace Chaplin\Model\Video;

use Chaplin\Auth;
use Chaplin\Model\Field\Hash;
use Chaplin\Model\Video;
use Chaplin\Model\User;
use Chaplin\Gateway;

class Comment extends Hash
{
    const FIELD_COMMENTID = 'CommentId';
    const FIELD_VIDEOID = 'VideoId';
    const FIELD_USERNAME = 'Username';
    const FIELD_COMMENT = 'Comment';
    //Feedback

    protected $arrFields = array(
        self::FIELD_COMMENTID => array('Class' => 'Chaplin\\Model\\Field\\FieldId'),
        self::FIELD_VIDEOID => array('Class' => 'Chaplin\\Model\\Field\\Field'),
        self::FIELD_USERNAME => array('Class' => 'Chaplin\\Model\\Field\\Field'),
        self::FIELD_COMMENT => array('Class' => 'Chaplin\\Model\\Field\\Field')
    );

    public static function create(
        Video $modelVideo,
        User $modelUser,
        $strComment
    ) {


        $comment = new self();
        $comment->setField(self::FIELD_COMMENTID, md5(uniqid()));
        $comment->setField(self::FIELD_VIDEOID, $modelVideo->getVideoId());
        $comment->setField(self::FIELD_USERNAME, $modelUser->getUsername());
        $comment->setField(self::FIELD_COMMENT, $strComment);
        return $comment;
    }

    public function getId()
    {
        return $this->getField(self::FIELD_COMMENTID, null);
    }

    public function getCommentId()
    {
        return $this->getField(self::FIELD_COMMENTID, null);
    }

    public function getUsername()
    {
        return $this->getField(self::FIELD_USERNAME, null);
    }

    private $modelUser;
    public function getUser()
    {
        if (is_null($this->modelUser)) {
            $this->modelUser = Gateway::getInstance()
                ->getUser()
                ->getByUsername($this->getUsername());
        }
        return $this->modelUser;
    }

    public function getComment()
    {
        return $this->getField(self::FIELD_COMMENT, null);
    }

    public function isMine()
    {
        if (!Auth::getInstance()->hasIdentity()) {
            return false;
        }
        if (Auth::getInstance()->getIdentity()->getUser()->isGod()) {
            // God users own everything, mwuhahaha
            return true;
        }
        return Auth::getInstance()->getIdentity()->getUser()->getUsername() ==
            $this->getUsername();
    }
}
