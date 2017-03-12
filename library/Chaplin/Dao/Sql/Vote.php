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
class Chaplin_Dao_Sql_Vote
    extends Chaplin_Dao_Sql_Abstract
    implements Chaplin_Dao_Interface_Vote
{
    const TABLE = 'Votes';

    protected function _getTable()
    {
        return self::TABLE;
    }

    protected function _getPrimaryKey()
    {
        // Not single
        return null;
    }

    public function addVote(Chaplin_Model_User $modelUser, Chaplin_Model_Video $modelVideo, $intVote)
    {
        $this->_getAdapter()->query(
            'INSERT INTO '.self::TABLE.' SET '.
            'Vote = ?, Username = ?, VideoId = ? ON DUPLICATE KEY UPDATE Vote = ?',
            [   
                $intVote,
                $modelUser->getUsername(),
                $modelVideo->getVideoId(),
                $intVote
            ]
        );
    }

    protected function _sqlToModel(Array $arrSql)
    {
    }

    protected function _modelToSql(Array $arrModel)
    {
    }

    public function convertToModel($arrData)
    {
    }
}
