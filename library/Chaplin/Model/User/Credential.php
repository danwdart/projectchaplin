<?php
class Chaplin_Model_User_Credential extends Chaplin_Model_Abstract_Child
{
    const FIELD_ServiceName = 'ServiceName';
    const FIELD_ServiceURL  = 'ServiceURL';
    const FIELD_AccessToken = 'AccessToken';

    protected static $_arrFields = array(
        self::FIELD_ServiceName => 'Chaplin_Model_Field_Field',
        self::FIELD_ServiceURL  => 'Chaplin_Model_Field_Field',
        self::FIELD_AccessToken => 'Chaplin_Model_Field_Field'
    );

    public static function create(Chaplin_Model_User $modelUser, $strServiceName, $strServiceURL, $strAccessToken)
    {
        $credential = new self($modelUser);
        $credential->_setField(self::FIELD_ServiceName, $strServiceName);
        $credential->_setField(self::FIELD_ServiceURL,  $strServiceURL);
        $credential->_setField(self::FIELD_AccessToken, $strAccessToken);
        return $credential;
    }

    public function getServiceName()
    {
        return $this->_getField(self::FIELD_ServiceName, null);
    }

    public function getServiceURL()
    {
        return $this->_getField(self::FIELD_ServiceURL, null);
    }

    public function getAccessToken()
    {
        return $this->_getField(self::FIELD_AccessToken, null);
    }
}
