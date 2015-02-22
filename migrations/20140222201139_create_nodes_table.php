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
        	->addColumn('NodeId', 'string', ['size' => 50])
        	->addColumn('IP', 'string', ['size' => 29])
        	->addColumn('Name', 'string', ['size' => 30])
        	->addColumn('Active', 'bool')
        	->create();
    }
}