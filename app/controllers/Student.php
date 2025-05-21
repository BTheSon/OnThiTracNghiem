<?php
namespace App\Controllers;
use App\Core\Controller;

use function App\Includes\navigate;

class Student extends Controller
{
    public function __construct() {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'hs') {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            navigate('/auth/login');
            exit();
        }
    }
    
    public function index(): void {
        $this->home();
    }

    public function home(): void{
        $this->view('layouts/main_layout.php', 
                    [
                        'sidebar' => 'hocsinh/partials/menu.php',
                        'content' => 'hocsinh/pages/tat-ca-cac-lop.php'
                    ],
                    [
                        'CSS_FILE' => [
                            'public/css/hocsinh.css'
                            
                        ]
                    ]);
    }

    public function review_materials(): void {
        $this->view('layouts/main_layout.php', 
                    [
                        'sidebar' => 'hocsinh/partials/menu.php',
                        'content' => 'hocsinh/pages/on-tap.php'
                    ],
                    [
                        'CSS_FILE' => [
                            'public/css/hocsinh.css'
                        ]
                    ]);
    }

    public function assigned_tests(): void {
        $this->view('layouts/main_layout.php', 
                    [
                        'sidebar' => 'hocsinh/partials/menu.php',
                        'content' => 'hocsinh/pages/de-kiem-tra.php'
                    ],
                    [
                        'CSS_FILE' => [
                            'public/css/hocsinh.css'
                        ]
                    ]);
    }
}