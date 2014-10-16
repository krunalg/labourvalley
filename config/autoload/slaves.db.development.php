<?php

return array(
    'db' => array(
        'slave' => array(
            'slave1' => array(
                'driver' => 'Pdo',
                'username' => 'root',
                'password' => '123456',
                'dsn' => 'mysql:dbname=zf_demo;host=localhost',
                'driver_options' => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
                )
            )
        )
    )
);
