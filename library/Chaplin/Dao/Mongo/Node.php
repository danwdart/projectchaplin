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
 * @package    Project Chaplin
 * @author     Dan Dart
 * @copyright  2012-2013 Project Chaplin
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version    git
 * @link       https://github.com/dandart/projectchaplin
**/
class Chaplin_Dao_Mongo_Node
    extends Chaplin_Dao_Mongo_Abstract
    implements Chaplin_Dao_Interface_Node
{
    const COLLECTION = 'Nodes';

    protected function _getCollectionName()
    {
        return self::COLLECTION;
    }
    
    public function getAllNodes()
    {
        $arrQuery = array();
        
        $cursor = $this->_getCollection()->find($arrQuery);
        
        return new Chaplin_Iterator_Dao_Mongo_Cursor($cursor, $this);
    }
    
    public function getByNodeId($strNodeId)
    {
        $arrQuery = array(
            Chaplin_Model_Node::FIELD_NODEID => $strNodeId
        );
        
        $arrNode = $this->_getCollection()->findOne($arrQuery);
        
        if(is_null($arrNode)) {
            throw new Chaplin_Dao_Exception_Node_NotFound($strNodeId);
        }
        return $this->convertToModel($arrNode);
    }

    public function convertToModel($arrNode)
    {
        return Chaplin_Model_Node::createFromDao($this, $arrNode);
    }

    public function delete(Chaplin_Model_Node $modelNode)
    {
        $this->_delete($modelNode);
    }

    public function save(Chaplin_Model_Node $modelNode)
    {
        $this->_save($modelNode);
    }
}
