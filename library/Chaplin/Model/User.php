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
class Chaplin_Model_User extends Chaplin_Model_Field_Hash
{
    const FIELD_Username = 'Username';
    const FIELD_Password = 'Password';
    const FIELD_Nick = 'Nick';
    const FIELD_Email = 'Email';
    const FIELD_UserTypeId = 'UserTypeId';
    const FIELD_HASH = 'Hash';
    const FIELD_VALIDATION = 'Validation';
    const CHILD_ASSOC_Credentials = 'Credentials';

    const SALT = 'dguqwtduR^%$*%%';

    const HASH_SHA512 = 'sha512';

    protected $_arrFields = [
        self::FIELD_Username => ['Class' => 'Chaplin_Model_Field_FieldId'],
        self::FIELD_Password => ['Class' => 'Chaplin_Model_Field_Field'],
        self::FIELD_Nick => ['Class' => 'Chaplin_Model_Field_Field'],
        self::FIELD_Email => ['Class' => 'Chaplin_Model_Field_Field'],
        self::FIELD_UserTypeId => ['Class' => 'Chaplin_Model_Field_Field'],
        self::FIELD_VALIDATION => ['Class' => 'Chaplin_Model_Field_Field'],
        self::FIELD_HASH => ['Class' => 'Chaplin_Model_Field_Field'],
        self::CHILD_ASSOC_Credentials => [
            'Class' => 'Chaplin_Model_Field_Collection'
        ]
    ];

    public static function create($strUsername, $strPassword)
    {
        $modelUser = new self();
        $modelUser->_bIsNew = true;
        $modelUser->_setField(self::FIELD_Username, self::encodeUsername($strUsername));
        $modelUser->setPassword($strPassword);
        return $modelUser;
    }

    protected function _getPK()
    {
        return self::FIELD_Username;
    }

    public function getId()
    {
        return $this->_getField(self::FIELD_Username, null);
    }

    public function toArray()
    {
        $arrOut = parent::toArray();
        unset($arrOut[self::FIELD_Password]);
        unset($arrOut[self::FIELD_VALIDATION]);
        unset($arrOut[self::FIELD_HASH]);
        return $arrOut;
    }

    public static function encodeUsername($strUsername)
    {
        return strtolower($strUsername);
    }

    /**
 * I'm not sure about this
*/
    public static function encodePassword($strPassword)
    {
        return hash('sha512', self::SALT.$strPassword, false);
    }

    public function verifyPassword($strPassword)
    {
        return (self::encodePassword($strPassword) == $this->_getField(self::FIELD_Password, null));
    }

    public function setPassword($strPassword)
    {
        $this->_setField(self::FIELD_Password, self::encodePassword($strPassword));
        $this->_setField(self::FIELD_HASH, self::HASH_SHA512);
    }

    public function resetPassword()
    {
        $strValidationToken = md5(uniqid());
        $this->_setField(self::FIELD_VALIDATION, $strValidationToken);
        $this->_setField(self::FIELD_Password, '');
        return $strValidationToken;
    }

    public function getUsername()
    {
        return $this->_getField(self::FIELD_Username, null);
    }

    public function getNick()
    {
        return $this->_getField(self::FIELD_Nick, null);
    }

    public function setNick($strNick)
    {
        $this->_setField(self::FIELD_Nick, $strNick);
    }

    public function getEmail()
    {
        return $this->_getField(self::FIELD_Email, null);
    }

    public function getMessages()
    {
        return new ArrayObject;
    }

    public function setEmail($strEmail)
    {
        $this->_setField(self::FIELD_Email, $strEmail);
    }

    public function getUserType()
    {
        return new Chaplin_Model_User_Helper_UserType($this->_getField(self::FIELD_UserTypeId, Chaplin_Model_User_Helper_UserType::ID_GUEST));
    }

    public function isGod()
    {
        return (Chaplin_Model_User_Helper_UserType::TYPE_GOD == $this->getUserType()->getUserType());
    }

    public function setUserType(Chaplin_Model_User_Helper_UserType $helperUserType)
    {
        $this->_setField(self::FIELD_UserTypeId, $helperUserType->getUserTypeId());
    }

    public function __get($strProperty)
    {
        return $this->_getField($strProperty, null);
    }

    public function __set($strProperty, $strValue)
    {
        $this->_setField($strProperty, $strValue);
    }

    public function delete()
    {
        return Chaplin_Gateway::getInstance()->getUser()->delete($this);
    }

    public function save()
    {
        return Chaplin_Gateway::getInstance()->getUser()->save($this);
    }
}
