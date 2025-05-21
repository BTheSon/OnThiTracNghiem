<?php
namespace App\Controllers;
use App\Core\Controller;

use function App\Includes\navigate;

class Teacher extends Controller
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
                        'sidebar' => 'giaovien/partials/menu.php',
                        'content' => 'giaovien/pages/tat-ca-lop-hoc.php'
                    ],
                    [
                        'cssFiles' => [
                            'public/css/giaovien.css'
                        ]
                    ]);
    }
}