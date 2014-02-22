<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    public function change()
    {
        $this->table('Users', [
            'id' => false,
            'primary_key' => 'Username'
        ])
            ->addColumn('Username', 'string')
            ->addColumn('Password', 'string', ['limit' => 128])
            ->addColumn('Nick', 'string', ['limit' => 30])
            ->addColumn('Email', 'string')
            ->addColumn('Hash', 'string', ['limit' => 20])
            ->addColumn('Validation', 'string', ['limit' => 32])
            ->addColumn('UserTypeId', 'tinyint')
            ->create();
    }
}