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
class Chaplin_Model_Playlist extends Chaplin_Model_Field_Hash
{
    const FIELD_ID = 'PlaylistId';
    const FIELD_USERNAME = 'Username';
    const FIELD_NAME = 'Name';
    const FIELD_PRIVACY = 'Privacy';

    protected $_arrFields = array(
        self::FIELD_ID => array('Class' => 'Chaplin_Model_Field_FieldId'),
        self::FIELD_USERNAME => array('Class' => 'Chaplin_Model_Field_Field'),
        self::FIELD_NAME => array('Class' => 'Chaplin_Model_Field_Field'),
        self::FIELD_PRIVACY => array('Class' => 'Chaplin_Model_Field_Field')
    );

    public static function create(Chaplin_Model_User $modelUser, $strName, $bPrivate = false)
    {
        $playlist = new self();
        $playlist->_bIsNew = true;
        $playlist->_setField(self::FIELD_ID, md5(uniqid()));
        $playlist->_setField(self::FIELD_USERNAME, $modelUser->getUsername());
        $playlist->_setField(self::FIELD_NAME, $strName);
        $playlist->_setField(self::FIELD_PRIVACY, $bPrivate?
            Chaplin_Model_Video_Privacy::ID_PRIVATE:
            Chaplin_Model_Video_Privacy::ID_PUBLIC
        );
        return $playlist;
    }

    protected function _getPK()
    {
        return self::FIELD_ID;
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

    public function getId()
    {
        return $this->_getField(self::FIELD_ID, null);
    }

    public function getPlaylistId()
    {
        return $this->getId();
    }

    public function getName()
    {
        return $this->_getField(self::FIELD_NAME, null);
    }

    public function getUsername()
    {
        return $this->_getField(self::FIELD_USERNAME, null);
    }

    public function delete()
    {
        return Chaplin_Gateway::getInstance()
            ->getPlaylist()
            ->delete($this);
    }

    public function save()
    {
        return Chaplin_Gateway::getInstance()
            ->getPlaylist()
            ->save($this);
    }
}
