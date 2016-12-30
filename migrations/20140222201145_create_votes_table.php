<?php

use Phinx\Migration\AbstractMigration;

class CreateVotesTable extends AbstractMigration
{
    public function change()
    {
    	$this->table('Votes', [
            'id' => false,
            'primary_key' => ['Username', 'VideoId']
        ])
        	->addColumn('Username', 'string', ['length' => 255])
        	->addColumn('VideoId', 'string', ['length' => 50])
        	->addColumn('Vote', 'boolean')
        	->create();
    }
}
