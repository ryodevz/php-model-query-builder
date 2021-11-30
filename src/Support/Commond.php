<?php

namespace Ryodevz\PHPModelQueryBuilder\Support;

class Commond extends Connection
{
    protected $table;

    protected $query;

    protected $columns = '*';

    public function orderBy(string $column, string $type = 'ASC')
    {
        $this->query = $this->query . " ORDER BY `{$column}` {$type}";

        return new static;
    }

    public function limit(int $limit)
    {
        $this->query = $this->query . " LIMIT {$limit}";

        return new static;
    }

    public function orWhere(string $column, $value, string $operator = '=')
    {
        $this->query = $this->query . " OR `{$column}` {$operator} '$value'";

        return new static;
    }

    public function where(string $column, $value, string $operator = '=')
    {
        $this->query = $this->query . " WHERE `{$column}` {$operator} '$value'";

        return new static;
    }

    public function update(array $data)
    {
        return $this->conn()->query($this->updateBuildQuery($data));
    }

    public function first(array $columns = [])
    {
        $this->setColumns($columns);
        $this->limit(1);

        $query = $this->conn()->query($this->getBuildQuery());

        if ($query) {
            return $query->fetch_assoc();
        }

        return false;
    }

    public function find($id)
    {
        return $this->conn()->query("SELECT * FROM `" . $this->table . "` WHERE `" . $this->table . "`.`" . $this->getRouteKeyName() . "` = " . $id)->fetch_assoc();
    }

    public function get(array $columns = [])
    {
        $this->setColumns($columns);

        return $this->conn()->query($this->getBuildQuery())->fetch_all(MYSQLI_ASSOC);
    }

    public function delete()
    {
        return $this->conn()->query($this->deleteBuildQuery());
    }

    public function create(array $data)
    {
        return $this->createBuildQuery($data);
    }

    protected function getRouteKeyName()
    {
        return 'id';
    }

    protected function updateBuildQuery($data)
    {
        $build = '';
        $count = count($data);

        $loop = 1;
        foreach ($data as $key => $value) {
            $build = $build . " `{$key}` = '{$value}'" . ($count == $loop ? '' : ',');

            $loop++;
        }

        return $this->query = "UPDATE `{" . $this->table . "}` SET {$build} " . $this->query;
    }

    protected function createBuildQuery($data)
    {
        $keys = [];
        $values = [];

        foreach ($data as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }

        $build_keys = $this->implodeKeys($keys);;
        $build_values = $this->implodeValues($values);;

        return $this->conn()->query("INSERT INTO `users` ({$build_keys}) VALUES ({$build_values})");
    }

    protected function deleteBuildQuery()
    {
        return "DELETE FROM `{" . $this->table . "}` " . $this->query;
    }

    protected function getBuildQuery()
    {
        return "SELECT " . $this->columns . " FROM " . $this->table . " " . $this->query;
    }

    protected function setColumns($columns)
    {
        if (!empty($columns)) {
            return $this->columns = implode(',', $columns);
        }
    }

    protected function implodeValues(array $data)
    {
        $build = '';
        $count = count($data);

        $loop = 1;
        foreach ($data as $value) {
            $build = $build . "'{$value}'" . ($count == $loop ? '' : ',');

            $loop++;
        }

        return $build;
    }

    protected function implodeKeys(array $data)
    {
        $build = '';
        $count = count($data);

        $loop = 1;
        foreach ($data as $key) {
            $build = $build . "`{$key}`" . ($count == $loop ? '' : ',');

            $loop++;
        }

        return $build;
    }

    protected function conn()
    {
        return (new Connection)->connection();
    }
}
