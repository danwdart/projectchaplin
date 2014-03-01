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
        	->addColumn('Username', 'string', ['size' => 255])
        	->addColumn('VideoId', 'string', ['size' => 50])
        	->addColumn('Vote', 'bool')
        	->create();
    }
}