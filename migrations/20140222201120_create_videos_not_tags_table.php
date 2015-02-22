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
        	->addColumn('VideoId', 'string', ['size' => 50])
        	->addColumn('TagId', 'string', ['size' => 50])
        	->create();
    }
}