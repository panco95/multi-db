<?php
require_once './vendor/autoload.php';

$config = [
    'user' => [
        'type' => 'mysql',
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'password' => '0825',
        'database' => 'test1',
        'charset' => 'utf8',
    ],
    'book' => [
        'type' => 'mysql',
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'password' => '0825',
        'database' => 'test2',
        'charset' => 'utf8',
    ]
];

$config2 = [
    'type' => 'mysql',
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'root',
    'password' => '0825',
    'database' => 'test1',
    'charset' => 'utf8',
];

\panco\facade\Db::setConfig($config);
$users = \panco\facade\Db::query('delete from `user` where id > 0', 'user');
var_dump($users);
$books = \panco\facade\Db::query('select * from `bag` where id > 0', 'book');
var_dump($books);

\panco\facade\Db::setConfig($config2);
$users = \panco\facade\Db::query('delete from `user` where id > 0', 'user');
var_dump($users);
