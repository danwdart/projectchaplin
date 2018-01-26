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

use Chaplin\Dao\DaoInterface;
use Zend_Db;
use Zend_Db_Adapter_Abstract;
use Chaplin\Dao\Sql\Exception\NoAdapter;
use DateTime;
use Chaplin\Model\Field\Hash;
use Exception;

abstract class SqlAbstract implements DaoInterface
{
    const DATETIME_SQL = 'Y-m-d H:i:s';

    private static $zendDb;

    public function __construct()
    {
        $db = Zend_Db::factory(
            getenv("SQL_ADAPTER"),
            [
                "host" => getenv("SQL_HOST"),
                "username" => getenv("SQL_USER"),
                "password" => getenv("SQL_PASSWORD"),
                "dbname" => getenv("SQL_DATABASE")
            ]
        );
        self::$zendDb = $db;
    }

    public static function setAdapter(Zend_Db_Adapter_Abstract $zendDb)
    {
        self::$zendDb = $zendDb;
    }

    protected function getAdapter()
    {
        if (!self::$zendDb instanceof Zend_Db_Adapter_Abstract) {
            throw new NoAdapter();
        }
        return self::$zendDb;
    }

    abstract protected function getTable();

    abstract protected function getPrimaryKey();

    abstract public function convertToModel($arrData);

    protected function modelToSql(array $arrModel)
    {
        return $arrModel;
    }

    protected function sqlToModel(array $arrSql)
    {
        return $arrSql;
    }

    private function textToSafe($strText)
    {
        if ('UTF-8' != mb_detect_encoding($strText)) {
            $strText = mb_convert_encoding($strText, 'UTF-8');
        }
        return $strText;
    }

    protected function timestampToSqlDateTime($intTimestamp)
    {
        $dt = DateTime::createFromFormat('U', $intTimestamp);
        return $dt->format(self::DATETIME_SQL);
    }

    protected function sqlDateTimeToTimestamp($strDateTime)
    {
        $dt = DateTime::createFromFormat(self::DATETIME_SQL, $strDateTime);
        return $dt->getTimestamp();
    }

    protected function saveModel(Hash $hash)
    {
        $strTable = $this->getTable();
        $collFields = $hash->getFields($this);
        $arrUpdate = $this->getUpdateArray($collFields);
        if ($hash->bIsNew()) {
            $this->getAdapter()->insert($strTable, $arrUpdate);
        } else {
            $strWhere = $this->getAdapter()->quoteInto($this->getPrimaryKey().' = ?', $hash->getId());
            $this->getAdapter()->update($strTable, $arrUpdate, $strWhere);
        }
    }

    protected function deleteWhere($strField, $strValue)
    {
        $strWhere = $this->getAdapter()->quoteInto($strField.' = ?', $strValue);
        $this->getAdapter()->delete($this->getTable(), $strWhere);
    }

    protected function deleteModel(Hash $hash)
    {
        return $this->deleteById($hash->getId());
    }

    protected function deleteById($strId)
    {
        return $this->deleteWhere($this->getPrimaryKey(), $strId);
    }

    private function getUpdateArray(array $collFields)
    {
        $arrUpdate = array();
        foreach ($collFields as $strFieldName => $objField) {
            if ($objField->bIsDirty()) {
                $strClass = get_class($objField);
                switch ($strClass) {
                    case 'Chaplin\\Model\\Field\\Field':
                    case 'Chaplin\\Model\\Field\\FieldId':
                        $arrUpdate[$strFieldName] = $this->textToSafe($objField->getValue(null));
                        break;
                    case 'Chaplin\\Model\\Field\\Readonly':
                        break;
                    default:
                        throw new Exception('Unmanaged class: '.$strClass);
                }
            }
        }
        return $this->modelToSql($arrUpdate);
    }
}
