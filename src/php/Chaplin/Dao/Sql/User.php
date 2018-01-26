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
 * @package   ProjectChaplin
 * @author    Dan Dart <chaplin@dandart.co.uk>
 * @copyright 2012-2018 Project Chaplin
 * @license   http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version   GIT: $Id$
 * @link      https://github.com/danwdart/projectchaplin
**/

namespace Chaplin\Dao\Sql;

use Chaplin\Dao\Sql\SqlAbstract;
use Chaplin\Dao\Interfaces\User as InterfaceUser;
use Chaplin\Iterator\Dao\Sql\Rows;
use Chaplin\Model\User as ModelUser;
use Chaplin\Dao\Exception\User\NotFound;

class User extends SqlAbstract implements InterfaceUser
{
    const TABLE = 'Users';

    const PK = 'Username';

    protected function getTable()
    {
        return self::TABLE;
    }

    protected function getPrimaryKey()
    {
        return self::PK;
    }

    public function getAllUsers()
    {
        $strSql = 'SELECT * FROM %s';
        $arrRows = $this->getAdapter()->fetchAll(sprintf($strSql, self::TABLE));
        return new Rows($arrRows, $this);
    }

    public function getByUsernameAndPassword($strUsername, $strPassword)
    {
        $arrCredentials = array(
            ModelUser::encodeUsername($strUsername),
            ModelUser::encodePassword($strPassword)
        );

        $strSql = 'SELECT * FROM %s WHERE Username = ? AND Password = ?';

        $arrRow = $this->getAdapter()->fetchRow(sprintf($strSql, self::TABLE), $arrCredentials);

        if (empty($arrRow)) {
            throw new NotFound();
        }

        return $this->convertToModel($arrRow);
    }

    public function getByUsername($strUsername)
    {
        $strSql = 'SELECT * FROM %s WHERE Username = ?';

        $arrRow = $this->getAdapter()->fetchRow(sprintf($strSql, self::TABLE), $strUsername);

        if (empty($arrRow)) {
            throw new NotFound();
        }

        return $this->convertToModel($arrRow);
    }


    public function save(ModelUser $modelUser)
    {
        return $this->saveModel($modelUser);
    }

    public function convertToModel($arrData)
    {
        return ModelUser::createFromData($this, $this->sqlToModel($arrData));
    }

    public function updateByToken($strToken, $strPassword)
    {
        $arrData = [
            ModelUser::FIELD_PASSWORD =>
                ModelUser::encodePassword($strPassword),
            ModelUser::FIELD_VALIDATION => null,
            ModelUser::FIELD_HASH => ModelUser::HASH_SHA512
        ];

        $intNumUpdated = $this->getAdapter()
            ->update(
                $this->getTable(),
                $arrData,
                $this->getAdapter()->quoteInto(
                    ModelUser::FIELD_VALIDATION.' = ?',
                    $strToken
                )
            );
    }
}
