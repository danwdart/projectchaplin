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
use Chaplin\Dao\Interfaces\Video as InterfaceVideo;
use Chaplin\Model\User as ModelUser;
use Chaplin\Model\Video as ModelVideo;
use Chaplin\Model\Video\Privacy;
use Chaplin\Iterator\Dao\Sql\Rows;
use Chaplin\Dao\Exception\Video\NotFound;

class Video extends SqlAbstract implements InterfaceVideo
{
    const TABLE = 'Videos';

    const PK = 'VideoId';

    protected function getTable()
    {
        return self::TABLE;
    }

    protected function getPrimaryKey()
    {
        return self::PK;
    }

    public function getFeaturedVideos(ModelUser $modelUser = null)
    {
        $strSql = 'SELECT * FROM %s WHERE '.
            ModelVideo::FIELD_PRIVACY.' = "'.Privacy::ID_PUBLIC.
            ((is_null($modelUser))? '"' :
            '" OR '.
            ModelVideo::FIELD_USERNAME .' = "'.$modelUser->getUsername().'"').
            ' ORDER BY TimeCreated DESC';
        $arrRows = $this->getAdapter()->fetchAll(sprintf($strSql, self::TABLE));
        return new Rows($arrRows, $this);
    }

    public function getByVideoId($strVideoId, ModelUser $modelUser = null)
    {
        $strSql = 'select Videos.*, '.
            '(SELECT COUNT(*) AS COUNT FROM Votes WHERE VideoId = ? AND Vote = 1) AS '.ModelVideo::FIELD_VOTESUP.
            ', (SELECT COUNT(*) AS COUNT FROM Votes WHERE VideoId = ? AND Vote = 0) AS '.ModelVideo::FIELD_VOTESDOWN.
            ', (SELECT Vote FROM Votes WHERE VideoId = ? '.((is_null($modelUser))?'':'AND Username = ?').' LIMIT 1) AS YourVote'.
            ' FROM %s WHERE %s = ? AND ('.
            ModelVideo::FIELD_PRIVACY.' = "'.Privacy::ID_PUBLIC.
            ((is_null($modelUser))? '")' :
            '" OR '.
            ModelVideo::FIELD_USERNAME .' = "'.$modelUser->getUsername().'")');

        $arrRow = $this->getAdapter()->fetchRow(
            sprintf($strSql, self::TABLE, self::PK),
            (is_null($modelUser) ?
                [$strVideoId, $strVideoId, $strVideoId, $strVideoId]:
                [$strVideoId, $strVideoId, $strVideoId, $modelUser->getUsername(), $strVideoId]
            )
        );
        if (false === $arrRow) {
            throw new NotFound($strVideoId);
        }
        return $this->convertToModel($arrRow);
    }

    public function getBySearchTerms($strSearchTerms)
    {
        // todo fill in
        return new Rows([], $this);
    }

    public function getByUser(ModelUser $modelUser)
    {
        // todo fill in
        return new Rows([], $this);
    }

    public function delete(ModelVideo $modelVideo)
    {
        return $this->deleteModel($modelVideo);
    }

    protected function sqlToModel(array $arrSql)
    {
        $arrModel = parent::sqlToModel($arrSql);
        unset($arrModel['Fb_Pos']);
        unset($arrModel['Fb_Neg']);
        if (isset($arrModel[ModelVideo::FIELD_TIMECREATED])) {
            $arrModel[ModelVideo::FIELD_TIMECREATED] =
                $this->sqlDateTimeToTimestamp(
                    $arrModel[ModelVideo::FIELD_TIMECREATED]
                );
        }
        return $arrModel;
    }

    protected function modelToSql(array $arrModel)
    {
        $arrSql = parent::modelToSql($arrModel);
        if (isset($arrSql[ModelVideo::FIELD_TIMECREATED])) {
            $arrSql[ModelVideo::FIELD_TIMECREATED] =
                $this->timestampToSqlDateTime(
                    $arrSql[ModelVideo::FIELD_TIMECREATED]
                );
        }
        return $arrSql;
    }

    public function save(ModelVideo $modelVideo)
    {
        return $this->saveModel($modelVideo);
    }

    public function convertToModel($arrData)
    {
        return ModelVideo::createFromData($this, $this->sqlToModel($arrData));
    }
}
