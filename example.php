<?php
require_once './vendor/autoload.php';

use panco\facade\Db;

// multi config
$config1 = [
    // database1
    'test1' => [
        'type' => 'mysql',
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'password' => '0825',
        'database' => 'test1',
        'charset' => 'utf8',
    ],
    // database2
    'test2' => [
        'type' => 'mysql',
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'password' => '0825',
        'database' => 'test2',
        'charset' => 'utf8',
    ]
];

// single config
$config2 = [
    'type' => 'mysql',
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'root',
    'password' => '0825',
    'database' => 'test1',
    'charset' => 'utf8',
];

// set connector pool
// 配置连接池数量，不管是多连接还是单连接，每个连接都是连接池
// 如果不需要连接池就不需要执行
Db::setPool(50);


// Use multi database connect
// 使用多个数据库连接
Db::setConfig($config1);
// no param and use test1 connect once
$users = Db::query('select * from `user` where id > 0 or id = 0', [], 'test1');
// with params and use test1 connect once
$users = Db::query('select * from `user` where id > ? or id = ?', [0, 0], 'test1');
// default connect test1 and with params
$users = Db::query('select * from `user` where id > ? or id = ?', [0, 0]);
// with params and use test2 connect once
$users = Db::query('select * from `user` where id > ? or id = ?', [0, 0], 'test2');
// toggle default connect to test2
Db::toggleConnect('test2');
// now connect is test2 with params
$users = Db::query('select * from `user` where id > ? or id = ?', [0, 0]);
// use connect test1 once and with params
$books = Db::query('select * from `bag` where username = ? and age = ?', ['panco', 24], 'test1');
// use default connect test2 and with params
$books = Db::query('select * from `bag` where username = ? and age = ?', ['panco', 24]);

// Use single database connect
// 使用单个数据库连接
Db::setConfig($config2);
// no param
$users = Db::query('select * from `user` where id > 0');
// with params
$books = Db::query('select * from `bag` where username = ? and age = ?', ['panco', 24]);

