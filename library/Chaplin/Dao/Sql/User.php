<?php
class Chaplin_Dao_Sql_User extends Chaplin_Dao_Sql_Abstract implements Chaplin_Dao_Interface_User
{
    const TABLE = 'Users';

    const PK = 'Username';

    protected function _getTable()
    {
        return self::TABLE;
    }

    protected function _getPrimaryKey()
    {
        return self::PK;
    }

    public function getByUsernameAndPassword($strUsername, $strPassword)
    {
        $arrCredentials = array(
            Chaplin_Model_User::encodeUsername($strUsername),
            Chaplin_Model_User::encodePassword($strPassword)
        );

        $strSql = 'SELECT * FROM %s WHERE Username = ? AND Password = ?';

        $arrRow = $this->_getAdapter()->fetchRow(sprintf($strSql, self::TABLE), $arrCredentials);

        if(empty($arrRow)) {
            throw new Chaplin_Dao_Exception_User_NotFound();
        }

        return $this->convertToModel($arrRow);
    }

    public function getByUsername($strUsername)
    {
        $strSql = 'SELECT * FROM %s WHERE Username = ?';

        $arrRow = $this->_getAdapter()->fetchRow(sprintf($strSql, self::TABLE), $strUsername);

        if(empty($arrRow)) {
            throw new Chaplin_Dao_Exception_User_NotFound();
        }

        return $this->convertToModel($arrRow);
    }


    public function save(Chaplin_Model_User $modelUser)
    {
        return $this->_save($modelUser);
    }

    public function convertToModel($arrData)
    {
        return Chaplin_Model_User::createFromData($this, $this->_sqlToModel($arrData));
    }
}
