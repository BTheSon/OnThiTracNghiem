<?php
namespace App\Controllers;
use App\Core\Controller;

/**
 * Test Controller
 * Dùng để kiểm tra các chức năng của ứng dụng.
 * Hiện tại chỉ có một trang index đơn giản.
 */
class Test extends Controller
{
    public function __construct() {
        
    }

    public function index() { 
        $this->view('tests/index.php');
    }
}