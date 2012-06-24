<?php
interface Chaplin_Dao_Interface_Node extends Chaplin_Dao_Interface
{
    public function getAllNodes();
    
    public function getByNodeId($strNodeId);
    
    public function delete(Chaplin_Model_Node $modelNode);

    public function save(Chaplin_Model_Node $modelNode);
}
