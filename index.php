<?php
use App\Core\App;
session_start();

define('BASE_DIR', realpath(__DIR__)); // Thư mục gốc của dự án

require_once './config/constants.php';
require_once './config/DBconfig.php';
require_once BASE_DIR . '/app/includes/ErrorDisplay.php';
require_once BASE_DIR . '/app/includes/helper.php';
require_once BASE_DIR . '/app/core/Autoloader.php';

$app = new App();
