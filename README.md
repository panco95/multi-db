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


使用例子：

```
use panco\facade\DB;

// DB::query()方法是执行sql操作，有三个参数，第一个是sql语句，参数变量用？表示，第二个参数为绑定参数变量数组，数量需与？个数一致，第三个参数是连接名，单个连接不用传第三个参数，多连接需要传数组key，默认不传就是默认连接，可以使用DB::toggle()方法切换默认连接

// 单个连接配置示例
$singleConfig = [
    'type' => 'mysql',      // 数据库类型，只测试过mysql
    'host' => '127.0.0.1',  // 数据库地址
    'port' => 3306,         // 端口
    'user' => 'root',       // 用户名
    'password' => '0825',   // 密码
    'database' => 'test',   // 数据库名称
    'charset' => 'utf8mb4', // 字符集
    'pool' => 50,           // 连接池连接数量
];

// 配置数据库连接
DB::setConfig($singleConfig);

// ping数据库，在使用workerman和swoole类似框架的时候，需要保持数据库长连接，建议设置一个定时器每分钟执行一次防止连接断开，这里的ping会ping所有连接池内的连接
DB::ping();

// DB::query()方法是执行sql操作，有三个参数，第一个是sql语句，参数变量用？表示，第二个参数为绑定参数变量数组，数量需与？个数一致，第三个参数是连接名，单个连接不用传第三个参数，多连接需要传数组key，默认不传就是默认连接，可以使用DB::toggle()方法切换默认连接
// 插入数据，返回1成功，0失败
$insert = Db::query("INSERT INTO `user` (`name`) VALUES (?)", ['panco']);
// 修改数据，返回1成功，0失败
$update = Db::query("UPDATE `user` SET `name` = ? WHERE `name` = ?", ['ss', 'panco']);
// 查询数据，返回数组
$select = Db::query("SELECT * FROM `user`");
// 删除数据，返回1成功，0失败
$delete = Db::query('DELETE FROM `user` WHERE `name` = ?', ['panco']);

// ------------------------------------------------------------------------------------- //

// 多个连接配置示例
$multiConfig = [
    'connect1' => [
        'type' => 'mysql',
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'password' => '0825',
        'database' => 'test',
        'charset' => 'utf8mb4',
        'pool' => 50,
    ],
    'connect2' => [
        'type' => 'mysql',
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'password' => '0825',
        'database' => 'test2',
        'charset' => 'utf8mb4',
        'pool' => 50
    ]
];

// 使用多个数据库连接
Db::setConfig($multiConfig);

// ping数据库，在使用workerman和swoole类似框架的时候，需要保持数据库长连接，建议设置一个定时器每分钟执行一次防止连接断开，这里的ping会ping所有连接池内的连接
DB::ping();

// DB::query()方法是执行sql操作，有三个参数，第一个是sql语句，参数变量用？表示，第二个参数为绑定参数变量数组，数量需与？个数一致，第三个参数是连接名，单个连接不用传第三个参数，多连接需要传数组key，默认不传就是默认连接，可以使用DB::toggle()方法切换默认连接
// 默认连接为配置项数组的第一项，query函数第三个参数是连接名称，不写默认使用默认连接，下面三个执行是一样的
$insert = Db::query("INSERT INTO `user` (`name`) VALUES (?)", ['panco'], 'connect1');
$insert = Db::query("INSERT INTO `user` (`name`) VALUES (?)", ['panco']);
// 使用第二个连接执行的两种方法
$update = Db::query("UPDATE `bag` SET `name` = ? WHERE `name` = ?", ['ss', 'panco'], 'connect2');
DB::toggleConnect('connect2'); // 切换默认数据库连接，后面的sql默认使用connect2连接
$update = Db::query("UPDATE `user` SET `name` = ? WHERE `name` = ?", ['ss', 'panco']);
// 这个执行connect1连接
$select = Db::query("SELECT * FROM `user`", [],'connect1');
// 这个执行connect2连接
$delete = Db::query('DELETE FROM `user` WHERE `name` = ?', ['panco']);
```