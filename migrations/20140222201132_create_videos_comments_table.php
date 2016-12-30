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
        	->addColumn('CommentId', 'string', ['length' => 50])
        	->addColumn('VideoId', 'string', ['length' => 50])
        	->addColumn('Username', 'string', ['length' => 50])
        	->addColumn('Comment', 'text')
        	->create();
    }
}
