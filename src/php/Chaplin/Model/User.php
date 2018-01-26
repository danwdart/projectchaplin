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

namespace Chaplin\Model;

use Chaplin\Model\Field\Hash;
use ArrayObject;
use Chaplin\Model\User\Helper\UserType;
use Chaplin\Gateway;

class User extends Hash
{
    const FIELD_USERNAME = 'Username';
    const FIELD_PASSWORD = 'Password';
    const FIELD_NICK = 'Nick';
    const FIELD_EMAIL = 'Email';
    const FIELD_USER_TYPE_ID = 'UserTypeId';
    const FIELD_HASH = 'Hash';
    const FIELD_VALIDATION = 'Validation';
    const CHILD_ASSOC_CREDENTIALS = 'Credentials';

    const SALT = 'dguqwtduR^%$*%%';

    const HASH_SHA512 = 'sha512';

    protected $arrFields = [
        self::FIELD_USERNAME => ['Class' => 'Chaplin\\Model\\Field\\FieldId'],
        self::FIELD_PASSWORD => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_NICK => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_EMAIL => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_USER_TYPE_ID => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_VALIDATION => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_HASH => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::CHILD_ASSOC_CREDENTIALS => [
            'Class' => 'Chaplin\\Model\\Field\\Collection'
        ]
    ];

    public static function create($strUsername, $strPassword)
    {
        $modelUser = new self();
        $modelUser->bIsNew = true;
        $modelUser->setField(self::FIELD_USERNAME, self::encodeUsername($strUsername));
        $modelUser->setPassword($strPassword);
        return $modelUser;
    }

    public function getId()
    {
        return $this->getField(self::FIELD_USERNAME, null);
    }

    public function toArray()
    {
        $arrOut = parent::toArray();
        unset($arrOut[self::FIELD_PASSWORD]);
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
        return (self::encodePassword($strPassword) == $this->getField(self::FIELD_PASSWORD, null));
    }

    public function setPassword($strPassword)
    {
        $this->setField(self::FIELD_PASSWORD, self::encodePassword($strPassword));
        $this->setField(self::FIELD_HASH, self::HASH_SHA512);
    }

    public function resetPassword()
    {
        $strValidationToken = md5(uniqid());
        $this->setField(self::FIELD_VALIDATION, $strValidationToken);
        $this->setField(self::FIELD_PASSWORD, '');
        return $strValidationToken;
    }

    public function getUsername()
    {
        return $this->getField(self::FIELD_USERNAME, null);
    }

    public function getNick()
    {
        return $this->getField(self::FIELD_NICK, null);
    }

    public function setNick($strNick)
    {
        $this->setField(self::FIELD_NICK, $strNick);
    }

    public function getEmail()
    {
        return $this->getField(self::FIELD_EMAIL, null);
    }

    public function getMessages()
    {
        return new ArrayObject;
    }

    public function setEmail($strEmail)
    {
        $this->setField(self::FIELD_EMAIL, $strEmail);
    }

    public function getUserType()
    {
        return new UserType($this->getField(self::FIELD_USER_TYPE_ID, UserType::ID_GUEST));
    }

    public function isGod()
    {
        return (UserType::TYPE_GOD == $this->getUserType()->getUserType());
    }

    public function setUserType(UserType $helperUserType)
    {
        $this->setField(self::FIELD_USER_TYPE_ID, $helperUserType->getUserTypeId());
    }

    public function __get($strProperty)
    {
        return $this->getField($strProperty, null);
    }

    public function __set($strProperty, $strValue)
    {
        $this->setField($strProperty, $strValue);
    }

    public function delete()
    {
        return Gateway::getInstance()->getUser()->delete($this);
    }

    public function save()
    {
        return Gateway::getInstance()->getUser()->save($this);
    }
}
