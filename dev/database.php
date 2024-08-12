<?php
return [
    'default'         => 'mysql',
    'time_query_rule' => [],
    'auto_timestamp'  => true,
    'datetime_format' => 'Y-m-d H:i:s',
    'connections'     => [
        'mysql' => [
            'type'            => 'mysql',
            'hostname'        => 'mariadb',
            'database'        => 'freeb',
            'username'        => 'dev',
            'password'        => 'dev@123123',
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
        ]
    ]
];
?>