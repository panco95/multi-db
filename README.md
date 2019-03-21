**Multi-db**

**Multi database connect library.**

**专注多个连接的数据库类库。**

`How to use：`

```
// use mutil connect
// 使用多个数据库连接
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


\panco\facade\Db::setConfig($config);
\panco\facade\Db::query('delete from `user` where id > 0', 'user');
```

```
// use single connect
// 使用单个数据库连接
$config2 = [
    'type' => 'mysql',
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'root',
    'password' => '0825',
    'database' => 'test1',
    'charset' => 'utf8',
];

\panco\facade\Db::setConfig($config2);
\panco\facade\Db::query('delete from `user` where id > 0', 'user');
```