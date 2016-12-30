<?php

use Phinx\Migration\AbstractMigration;

class CreateNodesTable extends AbstractMigration
{
    public function change()
    {
    	$this->table('Nodes', [
            'id' => false,
            'primary_key' => 'NodeId'
        ])
        	->addColumn('NodeId', 'string', ['length' => 50])
        	->addColumn('IP', 'string', ['length' => 29])
        	->addColumn('Name', 'string', ['length' => 30])
        	->addColumn('Active', 'boolean')
        	->create();
    }
}
