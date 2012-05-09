<?php
class Chaplin_Dao_Sql_User extends Chaplin_Dao_Sql_Abstract implements Chaplin_Dao_Interface_User
{
    const TABLE = 'User';

    protected function _getTable()
    {
        return self::TABLE;
    }

    public function getByUsernameAndPassword($strUsername, $strPassword)
    {
        $arrCredentials = array(
            $strUsername,
            $strPassword
        );

        $strSql = 'SELECT * FROM %s WHERE Username = ? AND Password = ?';

        $arrRow = $this->_getAdapter->fetchRow(sprintf($strSql, self::TABLE), $arrCredentials);

        if(empty($arrRow)) {
            throw new Chaplin_Dao_Exception_User_NotFound();
        }

        return Chaplin_Model_User::createFromDao($this, $arrRow);
    }

    public function save(Chaplin_Model_User $modelUser)
    {
        return $this->_save($modelUser);
    }
}
