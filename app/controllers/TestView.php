<?php
namespace App\Controllers;
use App\Core\Controller;

class TestView extends Controller{
    public function t(string  ...$viewName) : void {
        // chuyển đổi các tham số thành chuỗi
        $viewName = implode('/', $viewName);    

        $this->view($viewName);
    }
}