<?php

use Phinx\Migration\AbstractMigration;

class CreateCredentialsTable extends AbstractMigration
{
    public function change()
    {
        $this->table('Users_Credentials', [
            'id' => false,
            'primary_key' => 'CredentialId'
        ])
            ->addColumn('CredentialId', 'smallint')
            ->addColumn('Username', 'string')
            ->addColumn('Type', 'string', ['limit' => 10])
            ->addColumn('APIKey', 'string', ['limit' => 40])
            ->create();
    }
}