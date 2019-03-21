<?php

namespace panco;

class Db
{

    public $connect = array();
    public $defaultConnect = null;

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

    public function checkConnect($connect = null)
    {
        if (is_null($connect)) $connect = $this->defaultConnect;
        if (!$this->connect[$connect] instanceof \PDO) {
            die("Error: No database connect.");
        }
    }

    public function toggleConnect($connect)
    {
        if (isset($this->connect[$connect]) && $this->connect[$connect] instanceof \PDO) {
            $this->defaultConnect = $connect;
        }
    }

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