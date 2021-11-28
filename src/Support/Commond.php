<?php

namespace Ryodevz\PHPModelQueryBuilder\Support;

class Commond extends Connection
{
    protected static $table;

    protected static $query;

    protected static $columns = '*';

    public static function orderBy(string $column, string $type = 'ASC')
    {
        self::$query = self::$query . " ORDER BY `{$column}` {$type}";

        return new static;
    }

    public static function limit(int $limit)
    {
        self::$query = self::$query . " LIMIT {$limit}";

        return new static;
    }

    public static function orWhere(string $column, $value, string $operator = '=')
    {
        self::$query = self::$query . " OR `{$column}` {$operator} '$value'";

        return new static;
    }

    public static function where(string $column, $value, string $operator = '=')
    {
        self::$query = self::$query . " WHERE `{$column}` {$operator} '$value'";

        return new static;
    }

    public static function update(array $data)
    {
        return self::conn()->query(self::updateBuildQuery($data));
    }

    public static function first(array $columns = [])
    {
        self::setColumns($columns);
        self::limit(1);

        return self::conn()->query(self::getBuildQuery())->fetch_assoc();
    }

    public static function find($id)
    {
        return static::conn()->query("SELECT * FROM `" . static::$table . "` WHERE `" . static::$table . "`.`" . static::getRouteKeyName() . "` = " . $id)->fetch_assoc();
    }

    public static function get(array $columns = [])
    {
        self::setColumns($columns);

        return self::conn()->query(self::getBuildQuery())->fetch_all(MYSQLI_ASSOC);
    }

    public static function delete()
    {
        return self::conn()->query(self::deleteBuildQuery());
    }

    protected static function getRouteKeyName()
    {
        return 'id';
    }

    protected static function updateBuildQuery($data)
    {
        $build = '';
        $count = count($data);

        $loop = 1;
        foreach ($data as $key => $value) {
            $build = $build . " `{$key}` = '{$value}'" . ($count == $loop ? '' : ',');

            $loop++;
        }

        return self::$query = "UPDATE `{" . static::$table . "}` SET {$build} " . self::$query;
    }

    protected static function deleteBuildQuery()
    {
        return "DELETE FROM `{" . static::$table . "}` " . self::$query;
    }

    protected static function getBuildQuery()
    {
        return "SELECT " . self::$columns . " FROM " . static::$table . " " . self::$query;
    }

    protected static function setColumns($columns)
    {
        if (!empty($columns)) {
            return self::$columns = implode(',', $columns);
        }
    }

    protected static function conn()
    {
        return (new Connection)->connection();
    }
}
