<?php

namespace Ryodevz\PHPModelQueryBuilder\Support;

class Query extends Commond
{
    protected static $table;

    public static function select($table)
    {
        static::$table = $table;

        return new static;
    }
}
