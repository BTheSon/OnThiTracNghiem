<?php
namespace App\Controllers;
use App\Core\Controller;

use function App\Includes\navigate;

class Student extends Controller
{
    public function __construct() {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            navigate('/auth/login');
            exit();
        }
    }
    
    public function index(): void {
        $this->view('page/student/layout.php');
    }

    public function menu(): void{
        $this->view('page/student/menu.php');
    }
}