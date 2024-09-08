<?php
return [
    'default'         => 'pgsql',
    'time_query_rule' => [],
    'auto_timestamp'  => true,
    'datetime_format' => 'Y-m-d H:i:s',
    'connections'     => [
        'mysql' => [
            'type'            => 'mysql',
            'hostname'        => 'mariadb',
            'database'        => 'freeb',
            'username'        => 'root',
            'password'        => 'password',
            'hostport'        => '3306',
            'params'          => [
                \PDO::ATTR_CASE => \PDO::CASE_LOWER,
                \PDO::ATTR_EMULATE_PREPARES => true,
            ],
            'charset'         => 'utf8mb4',
            'prefix'          => 'sxo_',
            'deploy'          => 0,
            'rw_separate'     => false,
            'master_num'      => 1,
            'slave_no'        => '',
            'fields_strict'   => true,
            'break_reconnect' => false,
            'trigger_sql'     => false,
            'fields_cache'    => false,
        ],
        'pgsql' => [
            'type'            => 'pgsql',
            'hostname'        => 'postgres',
            'database'        => 'freeb',
            'username'        => 'postgres',
            'password'        => 'password',
            'hostport'        => '5432',
            'params'          => [
                \PDO::ATTR_CASE => \PDO::CASE_LOWER,
                \PDO::ATTR_EMULATE_PREPARES => true,
            ],
            'charset'         => 'utf8mb4',
            'prefix'          => 'sxo_',
            'deploy'          => 0,
            'rw_separate'     => false,
            'master_num'      => 1,
            'slave_no'        => '',
            'fields_strict'   => true,
            'break_reconnect' => false,
            'trigger_sql'     => false,
            'fields_cache'    => false,
        ]
    ]
];
?>