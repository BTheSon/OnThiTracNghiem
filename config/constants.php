<?php

// Tên của ứng dụng
define('APP_NAME', 'MindQuest');

// Thư mục gốc của dự án, được tính toán từ vị trí của file 'config'
define('BASE_DIR', dirname(__DIR__));

// Thư mục chứa các file view của ứng dụng
define('BASE_VIEWS_DIR', BASE_DIR . '/app/views');

// Thư mục chứa các file model của ứng dụng
define('BASE_MODELS_DIR', BASE_DIR . '/app/models');

// Thư mục chứa các file controller của ứng dụng
define('BASE_CONTROLLERS_DIR', BASE_DIR . '/app/controllers');

// Thư mục chứa các file tài sản (assets) như CSS, JavaScript, hình ảnh,...
define('BASE_ASSETS_CSS', BASE_DIR . '/public/css');

// Thư mục chứa các file cấu hình của dự án
define('BASE_CONFIG_DIR', BASE_DIR . '/config');

// URL gốc của ứng dụng
define('BASE_URL', (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST']);

// Host của cơ sở dữ liệu
define('DB_HOST', 'localhost');

// Tên của cơ sở dữ liệu
define('DB_NAME', 'mindquest_db');

// Tên người dùng để kết nối đến cơ sở dữ liệu
define('DB_USER', 'root');

// Mật khẩu để kết nối đến cơ sở dữ liệu
define('DB_PASSWORD', '');

// Charset của kết nối cơ sở dữ liệu
define('DB_CHARSET', 'utf8mb4');
