<?php
$arrChaplin = parse_ini_file(__DIR__.'/config/chaplin.ini');
return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/migrations'
    ],
    'environments' => [
        'default_migration_table' => 'Migrations',
        'default_database' => $arrChaplin['sql.params.dbname'],
        'chaplin' => [
            'adapter' => strtolower(substr($arrChaplin['sql.adapter'], 4)),
            'host' => $arrChaplin['sql.params.host'],
            'name' => $arrChaplin['sql.params.dbname'],
            'user' => $arrChaplin['sql.params.username'],
            'pass' => $arrChaplin['sql.params.password']
        ]
    ]
];
