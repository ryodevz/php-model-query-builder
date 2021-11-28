<?php

namespace Ryodevz\PHPModelQueryBuilder\Support\Facade;

class Hash
{
    public static function make(string $password, $algo = PASSWORD_DEFAULT)
    {
        return password_hash($password, $algo);
    }

    public static function verify(string $password, string $hash)
    {
        return password_verify($password, $hash);
    }
}
