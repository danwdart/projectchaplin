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
class Chaplin_Model_Channel
    extends Chaplin_Model_Field_Hash
{
    const FIELD_CHANNELID = 'ChannelId';
    const FIELD_FULLNAME = 'FullName';
    const FIELD_USERNAME = 'Username';

    protected $_arrFields = array(
        self::FIELD_CHANNELID => array('Class' => 'Chaplin_Model_Field_FieldId'),
        self::FIELD_FULLNAME => array('Class' => 'Chaplin_Model_Field_Field'),
        self::FIELD_USERNAME => array('Class' => 'Chaplin_Model_Field_Field'),
    );

    public static function create($strChannelId, $strFullName, Chaplin_Model_User $modelUser)
    {
        $modelChannel = new self();
        $modelChannel->_bIsNew = true;
        $modelChannel->_setField(self::FIELD_CHANNELID, $strChannelId);
        $modelChannel->_setField(self::FIELD_FULLNAME, $strFullName);
        $modelChannel->_setField(self::FIELD_USERNAME, $modelUser->getUsername());
        return $modelChannel;
    }

    public function getId()
    {
        return $this->_getField(self::FIELD_CHANNELID, null);
    }

    public function getChannelId()
    {
        return $this->_getField(self::FIELD_CHANNELID, null);
    }

    public function getFullName()
    {
        return $this->_getField(self::FIELD_FULLNAME, null);
    }

    public function getUser()
    {
        return Chaplin_Gateway::getUser()
            ->getByUsername(
                $this->_getField(self::FIELD_USERNAME, null)
            );
    }

    public function delete()
    {
        return Chaplin_Gateway::getInstance()->getChannel()->delete($this);
    }

    public function save()
    {
        return Chaplin_Gateway::getInstance()->getChannel()->save($this);
    }
}
