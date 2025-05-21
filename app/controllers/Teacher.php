<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\LopHocModel;

use function App\Includes\navigate;

class Teacher extends Controller
{
    private LopHocModel $model;
    public function __construct() {
        $this->model = $this->model('LopHocModel');
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

    public function home(): void {
        
        $allClass = $this->model->getByTeacherId($_SESSION['user_id']);
        

        $this->view('layouts/main_layout.php', 
                    [// layout partials
                        'sidebar' => 'giaovien/partials/menu.php',
                        'content' => 'giaovien/pages/tat-ca-lop-hoc.php'
                    ],
                    [// data
                        'info_classes'=> $allClass,
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
                        ],
                        'JS_FILE' => [
                            'public/js/form_tao_lophoc.js'
                        ]
                    ]);
    }
}