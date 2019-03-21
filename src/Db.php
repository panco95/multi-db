<?php

namespace panco;

class Db
{

    public $connect = array();
    public $defaultConnect = null;

    // 配置数据库
    public function setConfig($dbs)
    {
        if (count($dbs) < 1) {
            die('At least one database config!');
        }
        if (isset($dbs['type']) && isset($dbs['host'])) {
            $connector = new Connect($dbs['host'], $dbs['port'], $dbs['user'], $dbs['password'], $dbs['type'], $dbs['database'], $dbs['charset']);
            $this->connect[0] = $connector->connect;
            $this->defaultConnect = 0;
        } else {
            $i = 0;
            foreach ($dbs as $dbCode => $db) {
                $connector = new Connect($db['host'], $db['port'], $db['user'], $db['password'], $db['type'], $db['database'], $db['charset']);
                $this->connect[$dbCode] = $connector->connect;
                if ($i == 0) {
                    $this->defaultConnect = $dbCode;
                }
                $i++;
            }
        }
    }

    // 检测连接可用性
    public function checkConnect($dbCode = null)
    {
        if (is_null($dbCode)) $dbCode = $this->defaultConnect;
        if (!$this->connect[$dbCode] instanceof \PDO) {
            die("Error: No database connect.");
        }
    }

    // 切换默认连接
    public function toggleConnect($dbCode)
    {
        if (isset($this->connect[$dbCode]) && $this->connect[$dbCode] instanceof \PDO) {
            $this->defaultConnect = $dbCode;
        }
    }

    // 执行sql
    public function query($sql, $dbCode = null, $params = array())
    {
        if (is_null($dbCode)) {
            $dbCode = $this->defaultConnect;
        }

        $stmt = $this->connect[$dbCode]->prepare($sql);
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