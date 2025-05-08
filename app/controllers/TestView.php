<?php
namespace App\Controllers;
use App\Core\Controller;

class TestView extends Controller{
    public function t(string $viewName) : void {
        $this->view($viewName);
    }
}