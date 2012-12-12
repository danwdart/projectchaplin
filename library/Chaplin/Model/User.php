<?php
class Chaplin_Model_User extends Chaplin_Model_Field_Hash
{
    const FIELD_Username = Chaplin_Dao_Mongo_Abstract::FIELD_Id;
    const FIELD_Password = 'Password';
    const FIELD_Nick = 'Nick';
    const FIELD_Email = 'Email';
    const FIELD_UserTypeId = 'UserTypeId';
    const CHILD_ASSOC_Credentials = 'Credentials';

    const SALT = 'dguqwtduR^%$*%%';

    protected $_arrFields = array(
        self::FIELD_Username => 'Chaplin_Model_Field_FieldId',
        self::FIELD_Password => 'Chaplin_Model_Field_Field',
        self::FIELD_Nick => 'Chaplin_Model_Field_Field',
        self::FIELD_Email => 'Chaplin_Model_Field_Field',
        self::FIELD_UserTypeId => 'Chaplin_Model_Field_Field',
//        self::CHILD_ASSOC_Credentials => 'Chaplin_Model_Field_Collection_Assoc'
    );

    public static function create($strUsername, $strPassword)
    {
        $modelUser = new self();
        $modelUser->_setField(self::FIELD_Username, self::encodeUsername($strUsername));
        $modelUser->_setField(self::FIELD_Password, self::encodePassword($strPassword));
        return $modelUser;
    }

    public static function encodeUsername($strUsername)
    {
        return strtolower($strUsername);
    }

    /** I'm not sure about this */
    public static function encodePassword($strPassword)
    {
        return sha1(self::SALT.$strPassword);
    }
    
    public function verifyPassword($strPassword)
    {
        return (self::encodePassword($strPassword) == $this->_getField(self::FIELD_Password, null));
    }

    public function setPassword($strPassword)
    {
        $this->_setField(self::FIELD_Password, self::encodePassword($strPassword));
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
