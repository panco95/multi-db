**Multi-db**

**Multi database connect library, support connect pool. **

**专注多个连接的数据库类库，支持连接池，优势：**


`1、支持多连接操作或者多数据库操作 / Support multi connect or database`

`2、支持连接池（每个连接都独立支持） / Support connect pool`

`3、防SQL注入 / Prevent SQL injection`

 安装 / install:  `composer require panco/multi-db`

method:
```
use panco\facade\Db;


// 配置连接池数量，不设置默认为1，连接池就单个连接
// set connect pool, evert connect also have pool!
Db::setPool(50);

// 配置数据库连接数组，同时支持单连接和多连接
// config is array, see example.
Db::setConfig($config);

// 切换多连接的默认连接key
// toggle default connect, param is $config's key(string).
Db::toggleConnect('test1');

// 执行sql
// param1 is sql, param you can write ?, and param2 is ? to variable, param3 is connect $config's key(string).
// select sql return array, insert,delete and update sql return bool.
Db::query($sql, ['param1', 'param2'], 'test1');
```


Example:

```
use panco\facade\Db;

// 多连接配置演示
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
        'pool' => 10
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
        'pool' => 20
    ]
];

// 单连接配置演示
// single config
$config2 = [
    'type' => 'mysql',
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'root',
    'password' => '0825',
    'database' => 'test1',
    'charset' => 'utf8',
    'pool' => 50
];


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
```