<?php
namespace App\Controllers;
use App\Core\Controller;

class Student extends Controller
{
    public function __construct()
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            
            exit();
        }
    }
    
    public function index(): void
    {
        $this->view('page/student/layout.php');
    }
}