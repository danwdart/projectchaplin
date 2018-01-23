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
use Chaplin\Dao\Interfaces\Channel as InterfaceChannel;
use Chaplin\Iterator\Dao\Sql\Rows;
use Exception;
use Chaplin\Model\Channel as ModelChannel;

class Channel extends SqlAbstract implements InterfaceChannel
{
    const TABLE = 'Channels';

    const PK = 'ChannelId';

    protected function getTable()
    {
        return self::TABLE;
    }

    protected function getPrimaryKey()
    {
        return self::PK;
    }

    public function getAllChannels()
    {
        $strSql = 'SELECT * FROM %s';
        $arrRows = $this->getAdapter()->fetchAll(sprintf($strSql, self::TABLE));
        return new Rows($arrRows, $this);
    }

    public function getByChannelId($strChannelId)
    {
        $strSql = 'SELECT * FROM %s WHERE %s = ?';

        $arrRow = $this->getAdapter()->fetchRow(
            sprintf(
                $strSql,
                self::TABLE,
                self::PK
            ),
            $strChannelId
        );

        if (empty($arrRow)) {
            throw new Exception('No Channel named '.$strChannelId);
        }

        return $this->convertToModel($arrRow);
    }

    public function delete(ModelChannel $modelChannel)
    {
        return $this->deleteModel($modelChannel);
    }

    public function save(ModelChannel $modelChannel)
    {
        return $this->saveModel($modelChannel);
    }

    public function convertToModel($arrData)
    {
        return ModelChannel::createFromData($this, $this->sqlToModel($arrData));
    }
}
