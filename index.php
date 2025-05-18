<?php
use App\Core\App;
session_start();

require_once './config/constants.php';
require_once './config/DBconfig.php';
require_once BASE_DIR . '/app/includes/ErrorDisplay.php';
require_once BASE_DIR . '/app/includes/helper.php';
require_once BASE_DIR . '/app/core/Autoloader.php';

$app = new App();
