<?php
class Chaplin_Gateway_User
{
    private $_daoUser;

    public function __construct(Chaplin_Dao_Interface_User $daoUser)
    {
        $this->_daoUser = $daoUser;
    }

    public function getByUsernameAndPassword($strUsername, $strPassword)
    {
        return $this->_daoUser->getByUsernameAndPassword($strUsername, $strPassword);
    }
    
    public function getByUsername($strUsername)
    {
        return $this->_daoUser->getByUsername($strUsername);
    }
    
    public function delete(Chaplin_Model_User $modelUser)
    {
        $this->_daoUser->delete($modelUser);
    }

    public function save(Chaplin_Model_User $modelUser)
    {
        $this->_daoUser->save($modelUser);
    }
}
