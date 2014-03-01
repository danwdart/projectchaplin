<?php

use Phinx\Migration\AbstractMigration;

class CreateVideosCommentsTable extends AbstractMigration
{
    public function change()
    {
    	$this->table('Videos_Comments', [
            'id' => false,
            'primary_key' => 'CommentId'
        ])
        	->addColumn('CommentId', 'string', ['size' => 50])
        	->addColumn('VideoId', 'string', ['size' => 50])
        	->addColumn('Username', 'string', ['size' => 50])
        	->addColumn('Comment', 'text')
        	->create();
    }
}