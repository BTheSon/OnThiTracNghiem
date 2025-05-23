<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\HocSinhLopModel;

use function App\Includes\navigate;

class Student extends Controller
{
    private HocSinhLopModel $hocSinhLopModel;
    
    public function __construct() {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'hs') {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            navigate('/auth/login');
            exit();
        }
        $this->hocSinhLopModel = $this->model('HocSinhLopModel');
    }
    
    public function index(): void {
        $this->home();
    }

    public function home(): void{
            
        $result = $this->hocSinhLopModel-> getClassesByStudent($_SESSION['user_id']);

        $this->view('layouts/main_layout.php', 
                    [
                        'sidebar' => 'hocsinh/partials/menu.php',
                        'content' => 'hocsinh/pages/tat-ca-cac-lop.php'
                    ],
                    [   'info_classes' => $result,
                        'CSS_FILE' => [
                            'public/css/hocsinh.css'
                        ],
                        'JS_FILE' => [
                            'public/js/form-join-class.js'
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