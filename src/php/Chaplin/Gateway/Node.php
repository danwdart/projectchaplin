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

namespace Chaplin\Gateway;

use Chaplin\Gateway\GatewayAbstract;
use Chaplin\Dao\Interfaces\Node as InterfaceNode;
use Chaplin\Model\Node as ModelNode;

class Node extends GatewayAbstract
{
    private $daoNode;

    public function __construct(InterfaceNode $daoNode)
    {
        $this->daoNode = $daoNode;
    }

    public function getAllNodes()
    {
        return $this->daoNode->getAllNodes();
    }

    public function getByNodeId($strNodeId)
    {
        return $this->daoNode->getByNodeId($strNodeId);
    }

    public function delete(ModelNode $modelNode)
    {
        return $this->daoNode->delete($modelNode);
    }

    public function deleteById($strId)
    {
        return $this->daoNode->deleteById($strId);
    }

    public function save(ModelNode $modelNode)
    {
        return $this->daoNode->save($modelNode);
    }
}
