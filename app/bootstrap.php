<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Load general configuration
$config = require_once __DIR__ . '/../config/config.php';

// Load database configuration
$dbConfig = require_once __DIR__ . '/../config/database.php';

// Store the configuration in a global variable or a container for easy access
$GLOBALS['config'] = $config;
$GLOBALS['db'] = $dbConfig['database'];

// Load helper functions
require_once __DIR__ . '/../app/Helpers/view.php';
require_once __DIR__ . '/../app/Helpers/route.php';
require_once __DIR__ . '/../app/Helpers/processImage.php';
require_once __DIR__ . '/../app/Helpers/verify_identity.php';