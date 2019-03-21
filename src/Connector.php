<?php

namespace panco;

class Connect
{

    public $connect = null;

    public function __construct($host = '127.0.0.1', $port = 3306, $user = 'root', $password = 'root', $type = 'mysql', $database = 'test', $charset = 'utf8')
    {
        try {
            $dbh = new \PDO("{$type}:host={$host};port={$port};charset={$charset};dbname={$database}", $user, $password);
            $this->connect = $dbh;
        } catch (\Exception $e) {
            die(ucfirst($type) . " connect Error: " . $e->getMessage());
        }
    }

}