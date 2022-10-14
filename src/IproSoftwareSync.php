<?php

namespace IproSync;

class IproSoftwareSync
{
    public static $runsMigrations = true;


    public static function ignoreMigrations()
    {
        static::$runsMigrations = false;

        return new static();
    }
}
