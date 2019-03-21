<?php

namespace panco;

class Connect
{

    public $connect = null;

    public function __construct($host, $port, $user, $password, $type, $database, $charset)
    {
        try {
            $dbh = new \PDO("{$type}:host={$host};port={$port};charset={$charset};dbname={$database}", $user, $password);
            $this->connect = $dbh;
        } catch (\Exception $e) {
            die(ucfirst($type) . " connect Error: " . $e->getMessage());
        }
    }

}