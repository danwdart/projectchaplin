<?php
class Chaplin_Dao_Mongo_User
    extends Chaplin_Dao_Mongo_Abstract
    implements Chaplin_Dao_Interface_User
{
    const COLLECTION = 'Users';

    protected function _getCollectionName()
    {
        return self::COLLECTION;
    }

    public function getByUsernameAndPassword($strUsername, $strPassword)
    {
        $arrQuery = array(
            Chaplin_Model_User::FIELD_Username => Chaplin_Model_User::encodeUsername($strUsername),
            Chaplin_Model_User::FIELD_Password => Chaplin_Model_User::encodePassword($strPassword)
        );

        $arrUser = $this->_getCollection()->findOne($arrQuery);
        if(is_null($arrUser)) {
            throw new Chaplin_Dao_Exception_User_NotFound();
        }
        return $this->convertToModel($arrUser);
    }

    public function getByUsername($strUsername)
    {
        $arrQuery = array(
            Chaplin_Model_User::FIELD_Username => Chaplin_Model_User::encodeUsername($strUsername),
        );

        $arrUser = $this->_getCollection()->findOne($arrQuery);
        if(is_null($arrUser)) {
            throw new Chaplin_Dao_Exception_User_NotFound();
        }
        return $this->convertToModel($arrUser);
    }

    public function convertToModel($arrUser)
    {
        return Chaplin_Model_User::createFromDao($this, $arrUser);
    }

    public function delete(Chaplin_Model_User $modelUser)
    {
        $this->_delete($modelUser);
    }

    public function save(Chaplin_Model_User $modelUser)
    {
        $this->_save($modelUser);
    }
}
