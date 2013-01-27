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
        return Chaplin_Model_User::createFromData($this, $arrUser);
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
