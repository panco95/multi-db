<?php

namespace panco;

/**
 * 实际Db类（通常使用Db方法只需要调用Db外观类静态方法，无需创建本类并使用，请直接使用facade/Db）
 * Class Db
 * @package panco
 */
class Db
{

    /**
     * 存储单个或多个数据库连接
     * @var array
     */
    public $connect = array();

    /**
     * 默认数据库配置数组的key
     * @var null
     */
    public $defaultConnect = null;

    /**
     * 配置数据库并连接
     * @param $dbs @数据库配置，可一维数组（单连接）或者二维数组（多连接）
     */
    public function setConfig($dbs)
    {
        if (count($dbs) < 1) {
            die('At least one database config!');
        }
        if (isset($dbs['type']) && isset($dbs['host'])) {
            $connector = new Connector($dbs['host'], $dbs['port'], $dbs['user'], $dbs['password'], $dbs['type'], $dbs['database'], $dbs['charset']);
            $this->connect[0] = $connector->connect;
            $this->defaultConnect = 0;
        } else {
            $i = 0;
            foreach ($dbs as $connect => $db) {
                $connector = new Connector($db['host'], $db['port'], $db['user'], $db['password'], $db['type'], $db['database'], $db['charset']);
                $this->connect[$connect] = $connector->connect;
                if ($i == 0) {
                    $this->defaultConnect = $connect;
                }
                $i++;
            }
        }
    }

    /**
     * 检查数据库连接是否可用
     * @param null $connect @连接key
     */
    public function checkConnect($connect = null)
    {
        if (is_null($connect)) $connect = $this->defaultConnect;
        if (!$this->connect[$connect] instanceof \PDO) {
            die("Error: No database connect.");
        }
    }

    /**
     * 切换默认数据库
     * @param $connect @连接key
     */
    public function toggleConnect($connect)
    {
        if (isset($this->connect[$connect]) && $this->connect[$connect] instanceof \PDO) {
            $this->defaultConnect = $connect;
        }
    }

    /**
     * 执行数据库SQL语句
     * Select语句返回数组，Update、Delete、Insert返回bool
     * @param $sql @SQL语句
     * @param array $params @预编译参数数组，SQL语句中以 ? 符号替代参数，按此参数顺序预编译 ？
     * @param null $connect @多连接连接key，单个连接给此参数
     * @return array
     */
    public function query($sql, $params = array(), $connect = null)
    {
        if (is_null($connect)) {
            $connect = $this->defaultConnect;
        }

        $stmt = $this->connect[$connect]->prepare($sql);
        if (is_array($params) && count($params) > 0) {
            foreach ($params as $k => &$param) {
                $param = addslashes($param);
                $stmt->bindParam(intval($k) + 1, $param);
            }
        }

        $operation = substr($sql, 0, 6);
        if ($operation == 'select') {
            $result = array();
            if ($stmt->execute($params)) {
                while ($row = $stmt->fetch()) {
                    array_push($result, $row);
                }
            }
            return $result;
        } else {
            return $stmt->execute($params);
        }
    }

}