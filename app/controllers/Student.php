<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\DeThiModel;
use App\Models\HocSinhLopModel;
use App\Models\TaiLieuModel;
use App\Models\ThongBaoModel;

use function App\Includes\navigate;

/**
 * Student Controller
 * Xử lý các yêu cầu liên quan đến học sinh.
 * Bao gồm trang chủ, xem tài liệu, bài kiểm tra và thông báo.
 */
class Student extends Controller
{
    private HocSinhLopModel $hocSinhLopModel;
    private TaiLieuModel $taiLieuModel;
    private DeThiModel $deThiModel;
    private ThongBaoModel $thongBaoModel;

    public function __construct() {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'hs') {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            navigate('/auth/login');
            exit();
        }
        $this->hocSinhLopModel = $this->model('HocSinhLopModel');
        $this->taiLieuModel = $this->model('TaiLieuModel');
        $this->deThiModel = $this->model('DeThiModel');
        $this->thongBaoModel = $this->model('ThongBaoModel');
    }
    
    public function index(): void {
        $this->home();
    }

    /**
     * Hiển thị trang chủ của học sinh.
     * Hiển thị danh sách các lớp học mà học sinh đã tham gia.
     */
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

    /**
     * Hiển thị trang xem tài liệu của học sinh.
     * Hiển thị danh sách tài liệu theo lớp học.
     */
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
    /**
     * Hiển thị trang xem bài kiểm tra của học sinh.
     * Hiển thị danh sách các bài kiểm tra đã được giao cho học sinh và đã tham gia.
     * Cũng hiển thị các bài kiểm tra đã quá hạn.   
     */
    public function assigned_tests(): void {
        $exams = $this->deThiModel->getUnattendedExamsByStudent($_SESSION['user_id']);
        // Lấy các bài kiểm tra quá hạn
        $joinAndMissExam = $this->deThiModel->getExpiredAndAttendedExamsByStudent($_SESSION['user_id']);
        
        $this->view('layouts/main_layout.php', 
                    [
                        'sidebar' => 'hocsinh/partials/menu.php',
                        'content' => 'hocsinh/pages/de-kiem-tra.php'
                    ],
                    [
                        'exams' => $exams,
                        'give_name' => $joinAndMissExam,
                        'CSS_FILE' => [
                            'public/css/hocsinh.css'
                        ]
                    ]);
    }

    /**
     * Hiển thị trang xem thông báo của học sinh.
     * Hiển thị danh sách các thông báo theo lớp học.
     */
    public function class_preview(string $idClass): void {

        $classes = $this->hocSinhLopModel->getClassInfoByClassAndStudent($idClass, $_SESSION['user_id']);
        $document = $this->taiLieuModel->getByClass($idClass);
        $notifications = $this->thongBaoModel->getByClass($idClass);

        $this->view('layouts/main_layout.php', 
                    [
                        'sidebar' => 'hocsinh/partials/menu.php',
                        'content' => 'hocsinh/pages/lop-hoc.php'
                    ],
                    [
                        'classes' => $classes,
                        'documents' => $document,
                        'notifications' => $notifications,
                        'CSS_FILE' => [
                            'public/css/hocsinh.css'
                        ],
                        'JS_FILE' => [
                            'public/js/class-test.js'
                        ]
                    ]);
    }
}