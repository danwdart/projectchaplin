<?php
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
