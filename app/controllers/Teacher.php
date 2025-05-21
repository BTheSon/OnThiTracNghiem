<?php
namespace App\Controllers;
use App\Core\Controller;

use function App\Includes\navigate;

class Teacher extends Controller
{
    public function __construct() {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'gv') {
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
                        'sidebar' => 'giaovien/partials/menu.php',
                        'content' => 'giaovien/pages/tat-ca-lop-hoc.php'
                    ],
                    [
                        'CSS_FILE' => [
                            'public/css/giaovien.css'
                        ]
                    ]);
    }

    public function manager(): void {
        $this->view('layouts/main_layout.php', 
                    [
                        'sidebar' => 'giaovien/partials/menu.php',
                        'content' => 'giaovien/pages/quan-ly.php'
                    ],
                    [
                        'CSS_FILE' => [
                            'public/css/giaovien.css'
                        ]
                    ]);
    }

    public function add_class(): void {
        $this->view('layouts/main_layout.php', 
                    [
                        'sidebar' => 'giaovien/partials/menu.php',
                        'content' => 'giaovien/pages/tao-lop-hoc.php'
                    ],
                    [
                        'CSS_FILE' => [
                            'public/css/giaovien.css',
                            'public/css/form_tao_lophoc.css'
                        ]
                    ]);
    }
}