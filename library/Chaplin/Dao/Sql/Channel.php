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
 * @author    Kathie Dart <chaplin@kathiedart.uk>
 * @copyright 2012-2017 Project Chaplin
 * @license   http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version   GIT: $Id$
 * @link      https://github.com/kathiedart/projectchaplin
**/
class Chaplin_Dao_Sql_Channel
    extends Chaplin_Dao_Sql_Abstract
    implements Chaplin_Dao_Interface_Channel
{
    const TABLE = 'Channels';
    
    const PK = 'ChannelId';

    protected function _getTable()
    {
        return self::TABLE;
    }

    protected function _getPrimaryKey()
    {
        return self::PK;
    }

    public function getAllChannels()
    {
        $strSql = 'SELECT * FROM %s';
        $arrRows = $this->_getAdapter()->fetchAll(sprintf($strSql, self::TABLE));
        return new Chaplin_Iterator_Dao_Sql_Rows($arrRows, $this);
    }
    
    public function getByChannelId($strChannelId)
    {
        $strSql = 'SELECT * FROM %s WHERE %s = ?';

        $arrRow = $this->_getAdapter()->fetchRow(
            sprintf(
                $strSql, 
                self::TABLE,
                self::PK
            ), $strChannelId
        );

        if(empty($arrRow)) {
            throw new Exception('No Channel named '.$strChannelId);
        }

        return $this->convertToModel($arrRow);
    }
    
    public function delete(Chaplin_Model_Channel $modelChannel)
    {
        return $this->_delete($modelChannel);
    }

    public function deleteById($strId)
    {
        return $this->_deleteById($strId);
    }

    public function save(Chaplin_Model_Channel $modelChannel)
    {
        return $this->_save($modelChannel);
    }

    public function convertToModel($arrData)
    {
        return Chaplin_Model_Channel::createFromData($this, $this->_sqlToModel($arrData));
    }
}