<?php

// Tên của ứng dụng
define('APP_NAME', 'MindQuest');

// tên folder con mà bạn đã đặt tên trong dự án
define('SUB_DIR_NAME', '/OnThiTracNghiem');

// Thư mục gốc của dự án, được tính toán từ vị trí của file 'config'
define('BASE_DIR', dirname(__DIR__));

// Thư mục chứa các file view của ứng dụng
define('BASE_VIEWS_DIR', BASE_DIR . '/app/views');

// Thư mục chứa các file model của ứng dụng
define('BASE_MODELS_DIR', BASE_DIR . '/app/models');

// Thư mục chứa các file controller của ứng dụng
define('BASE_CONTROLLERS_DIR', BASE_DIR . '/app/controllers');

// URL gốc của ứng dụng (localhost/OnThiTracNghiem)
// Đường dẫn này sẽ được sử dụng để tạo các liên kết trong ứng dụng 
define('BASE_URL', (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . SUB_DIR_NAME);

/** đường dấn tương đối với thư mục dự án */
define('RELATIVE_STORAGE_PATH', '/storages');

/*** Đường dẫn tới folder chứa các file lưu trữ như hình ảnh, tài liệu */
define('BASE_STORAGE_DIR', BASE_DIR . RELATIVE_STORAGE_PATH);


/** định nghĩa các type của file mà giáo viên có thể tải lên */
define('ALLOWED_FILE_TYPES', [
    'pdf',
    'doc',
    'docx',
    'xls',
    'xlsx',
    'jpg',
    'jpeg',
    'png'
]);

define('DEFAULT_AVT_URL', 'public/image/avt.png');