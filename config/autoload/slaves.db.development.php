<?php
return array (
        'db' => array (
                'slave' => array (
                        'slave1' => array (
                                'driver' => 'Pdo',
                                'username' => 'viseshas_grub',
                                'password' => 'gf123#',
                                'dsn' => 'mysql:dbname=viseshas_grubdb;host=localhost',
                                'driver_options' => array (
                                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'' 
                                ) 
                        ) 
                ) 
        ) 
);
