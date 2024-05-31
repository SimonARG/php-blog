<?php

use App\Helpers\Database;

return [
  'database' => new Database([
    'dsn' => "{$_ENV['DB']}:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}",
    'username' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASS'],
  ]),
];