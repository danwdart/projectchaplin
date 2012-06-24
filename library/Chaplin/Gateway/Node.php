<?php
class Chaplin_Gateway_Node
{
    private $_daoNode;

    public function __construct(Chaplin_Dao_Interface_Node $daoNode)
    {
        $this->_daoNode = $daoNode;
    }

    public function getAllNodes()
    {
        return $this->_daoNode->getAllNodes();
    }

    public function getByNodeId($strNodeId)
    {
        return $this->_daoNode->getByNodeId($strNodeId);
    }
    
    public function delete(Chaplin_Model_Node $modelNode)
    {
        return $this->_daoNode->delete($modelNode);
    }

    public function save(Chaplin_Model_Node $modelNode)
    {
        return $this->_daoNode->save($modelNode);
    }
}
