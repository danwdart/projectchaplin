<?php

use Phinx\Migration\AbstractMigration;

class CreateVideosNotTagsTable extends AbstractMigration
{
    public function change()
    {
    	$this->table('Videos_MotTags', [
            'id' => false,
            'primary_key' => ['VideoId', 'TagId']
        ])
        	->addColumn('VideoId', 'string', ['length' => 50])
        	->addColumn('TagId', 'string', ['length' => 50])
        	->create();
    }
}
