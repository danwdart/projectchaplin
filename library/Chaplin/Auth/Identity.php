<?php
class Chaplin_Auth_Identity
{
    private $_modelUser;

    public function __construct(Chaplin_Model_User $modelUser)
    {
        $this->_modelUser = $modelUser;
    }

    public function getUser()
    {
        return $this->_modelUser;
    }
}
