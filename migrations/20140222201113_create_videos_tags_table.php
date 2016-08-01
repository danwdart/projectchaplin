<?php

use Phinx\Migration\AbstractMigration;

class CreateVideosTagsTable extends AbstractMigration
{
    public function change()
    {
    	$this->table('Videos_Tags', [
            'id' => false,
            'primary_key' => ['VideoId', 'TagId']
        ])
        	->addColumn('VideoId', 'string', ['length' => 50])
        	->addColumn('TagId', 'string', ['length' => 50])
        	->create();
    }
}
