<?php

namespace Ryodevz\PHPModelQueryBuilder\Support;

class Query
{
    public static function select($table)
    {
        return new Commond($table);
    }
}
