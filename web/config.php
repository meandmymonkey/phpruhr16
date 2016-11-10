<?php

$db = new \PDO(
    'mysql:host=localhost;dbname=phpruhr16',
    'root',
    '',
    [
        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
    ]
);
