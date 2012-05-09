<?php
class Chaplin_Auth_Adapter_Mongo implements Zend_Auth_Adapter_Interface
{
    public function __construct($strUsername, $strPassword)
    {
        $this->_strUsername = $strUsername;
        $this->_strPassword = $strPassword;
    }

    public function authenticate()
    {
        try {
            $modelUser = Chaplin_Gateway::getInstance()->getUser()->getByUsernameAndPassword($this->_strUsername, $this->_strPassword);
            $identity = new Chaplin_Auth_Identity($modelUser);
            return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS, $identity);
        } catch(Chaplin_Dao_Exception_User_NotFound $e) {
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, null, array($e->getMessage()));
        }
    }
}
