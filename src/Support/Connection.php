<?php

namespace Ryodevz\PHPModelQueryBuilder\Support;

class Connection
{
    protected $host;

    protected $username;

    protected $password;

    protected $database;

    protected $path;

    public function __construct()
    {
        $this->setPath();
        $this->setConfig();
    }

    protected function connection()
    {
        return new \mysqli($this->host, $this->username, $this->password, $this->database);
    }

    protected function setConfig()
    {
        $config = require $this->path;

        $this->host = $config['host'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->database = $config['database'];

        return $this;
    }

    protected function setPath()
    {
        if (file_exists('config.php')) {
            return $this->path = 'config.php';
        }

        return $this->path = __DIR__ . '/config.php';
    }
}
