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

namespace Chaplin\Dao\Sql\Video;

use Chaplin\Dao\Sql\SqlAbstract;
use Chaplin\Dao\Interfaces\Video\Comment as InterfaceComment;
use Chaplin\Dao\Exception\Video\NotFound;
use Chaplin\Model\Video\Comment as ModelComment;
use Chaplin\Iterator\Dao\Sql\Rows;

class Comment extends SqlAbstract implements InterfaceComment
{
    const TABLE = 'Videos_Comments';

    const PK = 'CommentId';

    protected function _getTable()
    {
        return self::TABLE;
    }

    protected function _getPrimaryKey()
    {
        return self::PK;
    }

    public function getById($strId)
    {
        $strSql = 'SELECT * FROM %s WHERE %s = ?';
        $arrRow = $this->_getAdapter()->fetchRow(sprintf($strSql, self::TABLE, self::PK), $strId);
        if (false === $arrRow) {
            throw new NotFound($strId);
        }
        return $this->convertToModel($arrRow);
    }

    public function getByVideoId($strVideoId)
    {
        $strSql = 'SELECT * FROM %s WHERE %s = ?';
        $arrRows = $this->_getAdapter()->fetchAll(
            sprintf(
                $strSql,
                self::TABLE,
                ModelComment::FIELD_VIDEOID
            ),
            $strVideoId
        );
        return new Rows($arrRows, $this);
    }

    public function delete(ModelComment $modelComment)
    {
        return $this->_delete($modelComment);
    }

    public function deleteById($strCommentId)
    {
        return $this->_deleteWhere($this->_getPrimaryKey(), $strCommentId);
    }

    protected function _sqlToModel(array $arrSql)
    {
        $arrModel = parent::_sqlToModel($arrSql);
        return $arrModel;
    }

    public function save(ModelComment $modelComment)
    {
        return $this->_save($modelComment);
    }

    public function convertToModel($arrData)
    {
        return ModelComment::createFromData(
            $this,
            $this->_sqlToModel($arrData)
        );
    }
}
