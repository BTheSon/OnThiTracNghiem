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
    
    // tải file view từ vị trí thứ mục BASE_VIEWS_DIR và data cho view
    protected function view(string $view, array $data = [], string $layout = "layouts/default_layout.php"): void {
        $viewPath = BASE_VIEWS_DIR .'/' . $view; 
        $layoutPath = BASE_VIEWS_DIR .'/' . $layout; 
        if (!file_exists($viewPath)) {
            throw new Exception("View file not found: " . $viewPath);
        }

        extract($data);
        ob_start();
        require_once $viewPath;
        $content = ob_get_clean();
        
        if (!$layout) {
            echo $content;
            return;
        }

        if (!file_exists($layoutPath)) {
            throw new Exception("Layout file not found: ".$layout);
        }

        require_once $layoutPath;
    }
}
