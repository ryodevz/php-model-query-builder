<?php

namespace Ryodevz\PHPModelQueryBuilder\Support;

class Model extends Commond
{
    protected $table;

    protected $query;

    public static function run()
    {
        return new static;
    }
}
