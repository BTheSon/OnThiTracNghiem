<?php
namespace App\Core;

use Exception;

class Controller
{
    // tải model sử dụng autoloading (Autoloader.php)
    protected function model(string $model): Model {
        $modelClass = 'App\\Models\\' . $model;
        if (class_exists($modelClass)) {
            return new $modelClass;
        } else {
            throw new Exception("Model class not found: " . $modelClass);
        }
    }
    
    // tải file view từ vị trí thứ mục BASE_VIEWS_DIR và data cho view, phải thêm .php sau view
    protected function view(string $view, array $data = []): void {
        $viewPath = BASE_VIEWS_DIR .'/' . $view; 
        $viewPath = str_replace(['\\', '//'], '/', $viewPath);  // chuẩn hóa đường dẫn viewPath
        if (!file_exists($viewPath)) 
            throw new Exception("View file not found: " . $viewPath);
        
        extract($data);
        require_once $viewPath;
    }
}
