<?php
namespace App\Core;
class App {
    protected $controller = 'Dashboard';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Xác định tên Controller theo namespace App\Controllers
        $controllerName = 'App\\Controllers\\' . (isset($url[0]) ? ucfirst($url[0]) : $this->controller);
        if (class_exists($controllerName)) {
            $this->controller = $controllerName;
            unset($url[0]);
        } else {
            // Sử dụng Controller mặc định nếu không tồn tại
            
            print_r($url);
            die("k tim thay controller");
            $this->controller = 'App\\Controllers\\' . $this->controller;
        }

        // Khởi tạo đối tượng Controller (autoloader sẽ tự động nạp file)
        $this->controller = new $this->controller;

        // Xác định method nếu có
        if (isset($url[1])) {
            $methodName = $this->convertKebabToSnake($url[1]);
            if (method_exists($this->controller, $methodName)) {
                $this->method = $methodName;
                unset($url[1]);
            }
        }

        // Lấy các tham số còn lại
        $this->params = $url ? array_values($url) : [];

        // Gọi Controller, Method và truyền các tham số nếu có
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // Hàm parseUrl để xử lý biến url từ yêu cầu
    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }

    // Hàm chuyển đổi kebab-case sang snake_case
    private function convertKebabToSnake($string) {
        return str_replace('-', '_', $string);
    }
}
