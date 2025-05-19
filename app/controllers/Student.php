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
        $this->menu();
    }

    public function menu(): void{
        $this->view('layouts/main_layout.php', 
                    [
                        'sidebar' => 'hocsinh/partials/menu.php',
                        'content' => 'hocsinh/pages/tat-ca-cac-lop.php'
                    ],
                    [
                        'cssFiles' => [
                            'public/css/hocsinh.css'
                            
                        ]
                    ]);
    }
}