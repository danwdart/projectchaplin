<?php
interface Chaplin_Dao_Interface_User extends Chaplin_Dao_Interface
{
    public function getByUsernameAndPassword($strUsername, $strPassword);
    
    public function getByUsername($strUsername);

    public function save(Chaplin_Model_User $modelUser);
}
