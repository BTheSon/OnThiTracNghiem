<?php
namespace App\Controllers;
use App\Core\Controller;

class Home extends Controller {

    public function index() {
        $this->view('index.php');
    }

    public function dangky() {
        $this->view('pages/dangky.php');        
    }

    public function dangnhap() {
        $this->view('pages/dangnhap.php');
    }
}