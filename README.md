**Multi-db**


`1、支持多连接操作或者多数据库操作`

`2、支持连接池（每个连接都独立支持）`

`3、防SQL注入`

`4、使用PDO封装，支持多种数据库类型`


安装: 
 
 `composer require panco/multi-db`


使用方法：
```
use panco\facade\Db;

// 配置数据库连接数组，同时支持单连接和多连接
Db::setConfig($config);

// ping数据库，在使用workerman和swoole需要定时ping防止数据库连接断开
Db::ping();

// 切换多连接的默认连接，后面可跟上query
Db::connect('test1');
Db::connect('test1')->query($sql);

// 执行sql，参数1：sql语句，参数2：sql的？代替的参数数组，参数3：连接名称
Db::query($sql, [$param1, $param2]);
```


使用例子：

```
use panco\facade\DB;

// DB::query()方法是执行sql操作，有2个参数，第一个是sql语句，参数变量用？表示，第二个参数为绑定参数变量数组，数量需与？的个数一致

//// 单个连接配置示例
$singleConfig = [
        // 类型（需PDO支持）
        'type'      => 'mysql',
        // 地址
        'host'      => '127.0.0.1',
        // 端口
        'port'      => 3306,
        // 用户名
        'user'      => 'root',
        // 密码
        'password'  => '0825',
        // 数据库名称
        'database'  => 'test1',
        // 字符集
        'charset'   => 'utf8mb4',
        // 连接池数量
        'pool'      => 5
];
// 配置数据库连接
DB::setConfig($singleConfig);

// ping数据库，在使用workerman和swoole类似框架的时候，需要保持数据库长连接，建议设置一个定时器每分钟执行一次防止连接断开，这里的ping会ping所有连接池内的连接
DB::ping();

// DB::query()方法是执行sql操作，有2个参数，第一个是sql语句，参数变量用？表示，第二个参数为绑定参数变量数组，数量需与？的个数一致
// 插入数据，返回1成功，0失败
$insert = Db::query("INSERT INTO `user` (`name`) VALUES (?)", ['panco']);
// 修改数据，返回1成功，0失败
$update = Db::query("UPDATE `user` SET `name` = ? WHERE `name` = ?", ['ss', 'panco']);
// 查询数据，返回数组
$select = Db::query("SELECT * FROM `user`");
// 删除数据，返回1成功，0失败
$delete = Db::query('DELETE FROM `user` WHERE `name` = ?', ['panco']);



// 多个连接配置示例
$multiConfig = [
    'test1' => [
        // 类型（需PDO支持）
        'type'      => 'mysql',
        // 地址
        'host'      => '127.0.0.1',
        // 端口
        'port'      => 3306,
        // 用户名
        'user'      => 'root',
        // 密码
        'password'  => '0825',
        // 数据库名称
        'database'  => 'test1',
        // 字符集
        'charset'   => 'utf8mb4',
        // 连接池数量
        'pool'      => 5
    ],

    'test2' => [
        // 类型（需PDO支持）
        'type'      => 'mysql',
        // 地址
        'host'      => '127.0.0.1',
        // 端口
        'port'      => 3306,
        // 用户名
        'user'      => 'root',
        // 密码
        'password'  => '0825',
        // 数据库名称
        'database'  => 'test2',
        // 字符集
        'charset'   => 'utf8mb4',
        // 连接池数量
        'pool'      => 5
    ]
];

// 使用多个数据库连接
Db::setConfig($multiConfig);

// ping数据库，在使用workerman和swoole类似框架的时候，需要保持数据库长连接，建议设置一个定时器每分钟执行一次防止连接断开，这里的ping会ping所有连接池内的连接
DB::ping();

// DB::query()方法是执行sql操作，有1个参数，第一个是sql语句，参数变量用？表示，第二个参数为绑定参数变量数组，数量需与？个数一致
// DB::connect()方法是切换当前操作的连接数组key，可后面跟上query方法
DB::connect('test1')->query("insert into `user` (`username`) VALUES(?)",['panco']);
```