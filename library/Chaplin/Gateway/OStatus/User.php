<?php
class Chaplin_Gateway_OStatus_User
{
    private $_daoOStatus_User;

    public function __construct(Chaplin_Dao_Mongo_OStatus_User $daoUser)
    {
        $this->_daoOStatus_User = $daoUser;
    }

    public function getAllByUser(Chaplin_Model_User $modelUser)
    {
        return $this->_daoOStatus_User->getAllByUser($modelUser);
    }
}
