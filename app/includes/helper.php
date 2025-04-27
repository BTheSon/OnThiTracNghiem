<?php
namespace App\Includes;

// Hàm lấy URL gốc của ứng dụng
function base_url($path = '') {
    // Lấy đường dẫn gốc của ứng dụng
    $base = str_replace('\\', '/', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
    if ($base === '/') {
        $base = '';
    }
    // Trả về base_url + đường dẫn (thêm đường dẫn nếu có)
    return $base . '/' . ltrim($path, '/');
}

// Hàm lấy đường dẫn tới tài nguyên tĩnh (CSS, JS, images)
function asset($path) {
    return base_url('public/' . ltrim($path, '/'));
}
