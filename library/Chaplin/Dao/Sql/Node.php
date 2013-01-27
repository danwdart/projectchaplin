<?php
class Chaplin_Dao_Sql_Node
	extends Chaplin_Dao_Sql_Abstract
	implements Chaplin_Dao_Interface_Node
{
	const TABLE = 'Nodes';
	
	const PK = 'NodeId';

	protected function _getTable()
	{
		return self::TABLE;
	}

	protected function _getPrimaryKey()
	{
		return self::PK;
	}

	public function getAllNodes()
	{

	}
    
    public function getByNodeId($strNodeId)
    {

    }
    
    public function delete(Chaplin_Model_Node $modelNode)
    {

    }

    public function save(Chaplin_Model_Node $modelNode)
    {

    }

    public function convertToModel($arrData)
    {
        return Chaplin_Model_Node::createFromData($this, $this->_sqlToModel($arrData));
    }
}