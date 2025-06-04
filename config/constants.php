<?php

$docRoot = realpath($_SERVER['DOCUMENT_ROOT']);         // /var/www
$projectDir = BASE_DIR;                         // /www//onthitracnghiem/

$subFolder = str_replace($docRoot, '', $projectDir);     // /2022/big_assignment/onthitracnghiem
$subFolder = str_replace('\\', '/', $subFolder);         // Windows compatible

// tên folder con
define('SUB_DIR_NAME', rtrim($subFolder, '/'));

/**
 * URL gốc của ứng dụng (localhost/OnThiTracNghiem)
 * Đường dẫn này sẽ được sử dụng để tạo các liên kết trong ứng dụng
 */
define('BASE_URL', (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . SUB_DIR_NAME);

// Tên của ứng dụng
define('APP_NAME', 'QUIZ');

/* Thư mục chứa các file view của ứng dụng*/
define('BASE_VIEWS_DIR', BASE_DIR . '/app/views');

/* Thư mục chứa các file model của ứng dụng */
define('BASE_MODELS_DIR', BASE_DIR . '/app/models');

/**Thư mục chứa các file controller của ứng dụng*/
define('BASE_CONTROLLERS_DIR', BASE_DIR . '/app/controllers');

/** đường dấn tương đối với thư mục chứa các file lưu trữ trong dự án */
define('RELATIVE_STORAGE_PATH', '/storages');

/*** Đường dẫn tới folder chứa các file lưu trữ như hình ảnh, tài liệu */
define('BASE_STORAGE_DIR', BASE_DIR . RELATIVE_STORAGE_PATH);

/** định nghĩa các đuôi mở rộng của file mà giáo viên có thể tải lên */
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

/**đường dẫn tới ảnh đại diện mặc định của người dùng */
define('DEFAULT_AVT_URL', 'public/image/avt.png');