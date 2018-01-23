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
use Chaplin\Dao\Interfaces\Node as InterfaceNode;
use Chaplin\Iterator\Dao\Sql\Rows;
use Exception;
use Chaplin\Model\Node as ModelNode;

class Node extends SqlAbstract implements InterfaceNode
{
    const TABLE = 'Nodes';

    const PK = 'NodeId';

    protected function getTable()
    {
        return self::TABLE;
    }

    protected function getPrimaryKey()
    {
        return self::PK;
    }

    public function getAllNodes()
    {
        $strSql = 'SELECT * FROM %s';
        $arrRows = $this->getAdapter()->fetchAll(sprintf($strSql, self::TABLE));
        return new Rows($arrRows, $this);
    }

    public function getByNodeId($strNodeId)
    {
        $strSql = 'SELECT * FROM %s WHERE %s = ?';

        $arrRow = $this->getAdapter()->fetchRow(
            sprintf(
                $strSql,
                self::TABLE,
                self::PK
            ),
            $strNodeId
        );

        if (empty($arrRow)) {
            throw new Exception('No node named '.$strNodeId);
        }

        return $this->convertToModel($arrRow);
    }

    public function delete(ModelNode $modelNode)
    {
        return $this->delete($modelNode);
    }

    public function deleteById($strId)
    {
        return $this->deleteById($strId);
    }

    public function save(ModelNode $modelNode)
    {
        return $this->save($modelNode);
    }

    public function convertToModel($arrData)
    {
        return ModelNode::createFromData($this, $this->sqlToModel($arrData));
    }
}
