<?php
namespace App\Includes;

/** Hàm lấy URL gốc của ứng dụng */
function base_url($path = '') {
    // Lấy đường dẫn gốc của ứng dụng
    $base = str_replace('\\', '/', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'));
    if ($base === '/') {
        $base = '';
    }
    // Trả về base_url + đường dẫn (thêm đường dẫn nếu có)
    return $base . '/' . ltrim($path, '/');
}

/** Hàm lấy URL của các tài nguyên như hình ảnh, css, js */
function asset($path) {
    return base_url('public/' . ltrim($path, '/'));
}

/** Hàm chuyển hướng đến một URL khác
 * /controller/action/parameter1/parameter2
 */
function navigate($url) {
    $url = BASE_URL . $url;
    header("Location: " . $url);
    exit();
}

// chuẩn hóa đường dẫn
function normalizePath($path): string {
    return str_replace(['\\', '//'], '/', $path);
}
/** Hàm trả về JSON và kết thúc script
 * Dùng để trả về dữ liệu dạng JSON cho AJAX requests
 */
function return_json(array $data): void {
    echo json_encode($data);
    exit();
}