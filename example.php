<?php
require_once './vendor/autoload.php';

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

// Use multi database connect
// 使用多个数据库连接
\panco\facade\Db::setConfig($config1);
$users = \panco\facade\Db::query('delete from `user` where id > 0 or id = 0', 'test1'); // no param
$users = \panco\facade\Db::query('delete from `user` where id > ? or id = ?', 'test1', [0, 0]); // with params

$users = \panco\facade\Db::query('delete from `user` where id > ? or id = ?', $params = [0, 0]); // default first config's database, is test1
$users = \panco\facade\Db::query('delete from `user` where id > ? or id = ?', 'test2', $params = [0, 0]); // use config's database2. is test2
\panco\facade\Db::toggleConnect('test2'); // toggle default connect to test2
$users = \panco\facade\Db::query('delete from `user` where id > ? or id = ?', $params = [0, 0]); // now connect is test2
$books = \panco\facade\Db::query('select * from `bag` where username = ? and age = ?', 'test1', ['panco', 24]); // change database once and with params
$books = \panco\facade\Db::query('select * from `bag` where username = ? and age = ?', $params = ['panco', 24]); // use default database and with params

// Use single database connect
// 使用单个数据库连接
\panco\facade\Db::setConfig($config2);
$users = \panco\facade\Db::query('delete from `user` where id > 0'); // no param
$books = \panco\facade\Db::query('select * from `bag` where username = ? and age = ?', $params = ['panco', 24]); // have params

