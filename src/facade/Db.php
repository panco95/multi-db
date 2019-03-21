<?php

namespace panco\facade;


class Db
{

    private static $db = null;

    private function __construct()
    {
    }

    public static function setConfig($dbs)
    {
        self::checkDbInstance();
        self::$db->setConfig($dbs);
    }

    public static function __callStatic($name, $arguments)
    {
        self::checkDbInstance();
        return self::$db->$name(...$arguments);
    }

    private static function checkDbInstance()
    {
        if (!self::$db instanceof \panco\Db) {
            self::$db = new \panco\Db();
        }
    }

}