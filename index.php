<?php

use Ryodevz\PHPModelQueryBuilder\Support\Models\User;

require_once 'vendor/autoload.php';

return User::where('username', $username)->get();
