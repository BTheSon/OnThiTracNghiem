<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\LopHocModel;
use App\Models\HocSinhLopModel;
use function App\Includes\navigate;

class Teacher extends Controller
{
    private LopHocModel $model;
    private HocSinhLopModel $hocSinhLopModel;
    public function __construct() {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'gv') {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            navigate('/auth/login');
            exit();
        }
        $this->model = $this->model('LopHocModel');
        $this->hocSinhLopModel = $this->model('HocSinhLopModel');
    }
    
    public function index(): void {
        $this->home();
        $this->destroy_session_class();
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
        $this->view('layouts/quanly_layout.php', 
                    [
                        'sidebar' => 'giaovien/partials/menu.php',
                        'navbar' => 'giaovien/partials/quanly_navbar.php',
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
    /**
     * data[infor_students] = [
     *      [
     *       'hs_id' => 1,
     *       'lh_id' => 'Nguyễn Văn A',
     *       'id' => '1',
     *       'ngay_tham_gia' => 'hs',
     *       'ho_ten' => 1,
     *       'email' => 1,
     *       'anh' => 1
     *      ],
     * ]
     */
    public function class_management(string $idClass): void {
        $this->create_session_class($idClass);
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $students = $this->hocSinhLopModel->getStudentsByClassAndName($idClass, $search);
        } else {
            $students = $this->hocSinhLopModel->getStudentsByClass($idClass);
        }

        $class = $this->model->getById($idClass);

        /** @var HocSinhThiModel $baithi */
        $baithi = $this->model('HocSinhThiModel');
        
        // duyệt và tính điểm trung bình cho từng học sinh
        foreach($students as &$student) {
            $diemtb = $baithi->getAveragePointByStudentAndClass($idClass, $student['hs_id']);
            if (empty($diemtb) || empty($diemtb['diem_tb_hs'])) {
                $student['diem_tb_hs'] = 0;
            } else {
                $student['diem_tb_hs'] = $diemtb['diem_tb_hs'];
            }
        }

        $this->view('layouts/main_layout.php', 
                    [
                        'sidebar' => 'giaovien/partials/menu.php',
                        'content' => 'giaovien/pages/quan-ly-lop.php'
                    ],
                    [
                        'info_students' => $students,
                        'info_classes' => $class,
                        'CSS_FILE' => [
                            'public/css/giaovien.css',
                            'public/css/gv-taoCauHoi.css'
                            
                        ],
                        'JS_FILE' => [
                            'public/js/load-form.js'
                        ]
                    ]);
    }

    private function create_session_class(string $idClass): void {
        $_SESSION['class_id'] = $idClass;
        $_SESSION['class_name'] = $this->model->getById($idClass)['ten_lop'];
    
    }

    private function destroy_session_class(): void {
        unset($_SESSION['class_id']);
        unset($_SESSION['class_name']);
    }

}