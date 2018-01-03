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
 * @link      https://github.com/kathiedart/projectchaplin
**/
class Chaplin_Dao_Sql_Video
    extends Chaplin_Dao_Sql_Abstract
    implements Chaplin_Dao_Interface_Video
{
    const TABLE = 'Videos';

    const PK = 'VideoId';

    protected function _getTable()
    {
        return self::TABLE;
    }

    protected function _getPrimaryKey()
    {
        return self::PK;
    }

    public function getFeaturedVideos(Chaplin_Model_User $modelUser = null)
    {
        $strSql = 'SELECT * FROM %s WHERE '.
            Chaplin_Model_Video::FIELD_PRIVACY.' = "'.Chaplin_Model_Video_Privacy::ID_PUBLIC.
            ((is_null($modelUser))? '"' : 
            '" OR '.
            Chaplin_Model_Video::FIELD_USERNAME .' = "'.$modelUser->getUsername().'"').
            ' ORDER BY TimeCreated DESC';
        $arrRows = $this->_getAdapter()->fetchAll(sprintf($strSql, self::TABLE));
        return new Chaplin_Iterator_Dao_Sql_Rows($arrRows, $this);
    }
    
    public function getByVideoId($strVideoId, Chaplin_Model_User $modelUser = null)
    {
        $strSql = 'select Videos.*, '.
            '(SELECT COUNT(*) AS COUNT FROM Votes WHERE VideoId = ? AND Vote = 1) AS '.Chaplin_Model_Video::FIELD_VOTESUP.
            ', (SELECT COUNT(*) AS COUNT FROM Votes WHERE VideoId = ? AND Vote = 0) AS '.Chaplin_Model_Video::FIELD_VOTESDOWN.
            ', (SELECT Vote FROM Votes WHERE VideoId = ? '.((is_null($modelUser))?'':'AND Username = ?').' LIMIT 1) AS YourVote'.
            ' FROM %s WHERE %s = ? AND ('.
            Chaplin_Model_Video::FIELD_PRIVACY.' = "'.Chaplin_Model_Video_Privacy::ID_PUBLIC.
            ((is_null($modelUser))? '")' : 
            '" OR '.
            Chaplin_Model_Video::FIELD_USERNAME .' = "'.$modelUser->getUsername().'")');

        $arrRow = $this->_getAdapter()->fetchRow(
            sprintf($strSql, self::TABLE, self::PK),
            (is_null($modelUser) ? 
                [$strVideoId, $strVideoId, $strVideoId, $strVideoId]:
                [$strVideoId, $strVideoId, $strVideoId, $modelUser->getUsername(), $strVideoId]
            )
        );
        if (false === $arrRow) {
            throw new Chaplin_Dao_Exception_Video_NotFound($strVideoId);
        }
        return $this->convertToModel($arrRow);
    }

    public function getBySearchTerms($strSearchTerms)
    {
        // todo fill in
        return new Chaplin_Iterator_Dao_Sql_Rows([], $this);
    }
    
    public function getByUser(Chaplin_Model_User $modelUser)
    {
        // todo fill in
        return new Chaplin_Iterator_Dao_Sql_Rows([], $this);
    }
            
    public function delete(Chaplin_Model_Video $modelVideo)
    {
        return $this->_delete($modelVideo);
    }

    protected function _sqlToModel(Array $arrSql)
    {
        $arrModel = parent::_sqlToModel($arrSql);
        unset($arrModel['Fb_Pos']);
        unset($arrModel['Fb_Neg']);
        if (isset($arrModel[Chaplin_Model_Video::FIELD_TIMECREATED])) {
            $arrModel[Chaplin_Model_Video::FIELD_TIMECREATED] =
                $this->_sqlDateTimeToTimestamp(
                    $arrModel[Chaplin_Model_Video::FIELD_TIMECREATED]
                );
        }
        return $arrModel;
    }

    protected function _modelToSql(Array $arrModel)
    {
        $arrSql = parent::_modelToSql($arrModel);
        if (isset($arrSql[Chaplin_Model_Video::FIELD_TIMECREATED])) {
            $arrSql[Chaplin_Model_Video::FIELD_TIMECREATED] =
                $this->_timestampToSqlDateTime(
                    $arrSql[Chaplin_Model_Video::FIELD_TIMECREATED]
                );
        }
        return $arrSql;
    }

    public function save(Chaplin_Model_Video $modelVideo)
    {
        return $this->_save($modelVideo);
    }

    public function convertToModel($arrData)
    {
        return Chaplin_Model_Video::createFromData($this, $this->_sqlToModel($arrData));
    }
}
