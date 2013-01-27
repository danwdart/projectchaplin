<?php
/**
 * This file is part of Project Chaplin.
 *
 * Project Chaplin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Project Chaplin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Project Chaplin. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    Project Chaplin
 * @author     Dan Dart
 * @copyright  2012-2013 Project Chaplin
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version    git
 * @link       https://github.com/dandart/projectchaplin
**/
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
