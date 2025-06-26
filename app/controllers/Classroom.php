<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\LopHocModel;
use App\Models\HocSinhLopModel;
use App\Models\HocSinhThiModel;
use App\Models\NguoiDungModel;

use function App\Includes\navigate;
use function App\Includes\return_json;

class Classroom extends Controller
{
    private LopHocModel $lopHocModel;
    private HocSinhLopModel $hocSinhLopModel;
    private HocSinhThiModel $hocSinhThiModel;
    private NguoiDungModel $nguoiDungModel;

    public function __construct() {
        $this->lopHocModel = $this->model('LopHocModel');
        $this->hocSinhLopModel = $this->model('HocSinhLopModel');
        $this->hocSinhThiModel = $this->model('HocSinhThiModel');
        $this->nguoiDungModel = $this->model('NguoiDungModel');
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            // Kiểm tra xem request có phải là AJAX không
            $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            
            if ($isAjax) {
                // Trả về JSON cho AJAX request
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập']);
                exit();
            } else {
                // Chuyển hướng đến trang đăng nhập cho non-AJAX request
                navigate('/auth/login'); // Hàm navigate được giả định là chuyển hướng
                exit();
            }
        }
    }

    // ajax
    public function create(): void {
        // Thiết lập các header cho phản hồi
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *'); // Cho phép tất cả domain (có thể giới hạn domain cụ thể)
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type, Accept'); 
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ten_lop = $_POST['tenlop'];
            $ma_lop = $_POST['malop'];
            $id_gv = $_SESSION['user_id'];
            // Kiểm tra xem người dùng có phải là giáo viên không
            if ($_SESSION['user_role'] !== 'gv') {
                echo json_encode(['success' => false, 'message' => 'Bạn không có quyền tạo lớp học.']);
                return;
            }
            // Validate input
            if (empty($ten_lop) || empty($ma_lop)) {
                echo json_encode(['success' => false, 'message' => 'Cần phải nhập tên lớp và mã lớp.']);
                return;
            }

            if ($this->lopHocModel->maLopExists($ma_lop)) {
                echo json_encode(['success' => false, 'message'=>'đã tồn tại mã lớp này.']);
                return;
            }
            // Tạo lớp học mới
            $result = $this->lopHocModel->create([
                                            'gv_id' => $id_gv,
                                            'ma_lop' => $ma_lop,
                                            'ten_lop' => $ten_lop,
                                            'mo_ta' => $_POST['mota'] ?? null
                                            ]);
            if ($result > 0) {
                echo json_encode(['success' => true, 'message' => 'Lớp học đã được tạo thành công.']);
            } elseif ($result === 0) {
                echo json_encode(['success' => false, 'message' => 'Lớp học đã tồn tại.']);
            } else {
                echo json_encode(['success' => false, 'message'=>'Có lỗi xảy ra trong quá trình tạo lớp học.']);
            }
            exit();
        } else {
            echo json_encode(['success' => false, 'message'=>'Có lỗi xảy ra trong quá trình tạo lớp học.']);
            exit();
        }
    }

    public function join() : void {
        // Thiết lập các header cho phản hồi
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *'); // Cho phép tất cả domain (có thể giới hạn domain cụ thể)
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type, Accept'); 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ma_lop = $_POST['malop'];
            $id_hs = $_SESSION['user_id'];
            
            // Kiểm tra xem người dùng có phải là học sinh không
            if ($_SESSION['user_role'] !== 'hs') {
                echo json_encode(['success' => false, 'message' => 'Bạn không có quyền tham gia lớp học.']);
                return;
            }

            // Validate input
            if (empty($ma_lop)) {
                echo json_encode(['success' => false, 'message' => 'Cần phải nhập mã lớp.']);
                return;
            }

            $idLop = $this->lopHocModel->getIdByMaLop($ma_lop);
            if ($idLop == 0) {
                echo json_encode(['success' => false, 'message' => 'Mã lớp không tồn tại.']);
                return;
            }
            // Kiểm tra xem học sinh đã tham gia lớp chưa
            if ($this->hocSinhLopModel->isStudentInClass($idLop, $id_hs)) {
                echo json_encode(['success' => false, 'message'=>'Bạn đã tham gia lớp học này rồi.']);
                return;
            }
            

            // Tham gia lớp học
            $result = $this->hocSinhLopModel->addStudentToClass($idLop, $id_hs);
            if ($result > 0) {
                echo json_encode(['success' => true, 'message' => 'Tham gia lớp học thành công.', ['id' => $result]]);
            } else{
                echo json_encode(['success' => false, 'message'=>'Có lỗi xảy ra trong quá trình tham gia lớp học.']);
            }
            exit();
        } else {
            echo json_encode(['success' => false, 'message'=>'Có lỗi xảy ra trong quá trình tham gia lớp học.']);
            exit();
        }
    }

    public function view_student_exams (string $student_id): void {
        // xem danh sách bài kiểm tra của học sinh trả về html
        $id_gv = $_SESSION['user_id'];

        $result = $this->hocSinhThiModel->getResultsByStudent($student_id);
        $userInfo = $this->nguoiDungModel->getById($student_id);
        // formai lại $result['trang_thai'] thành trạng thái dễ đọc dùng switch case với các giá trị:'chua_lam', 'dang_lam', 'da_nop', 'gian_doan'
        foreach ($result as &$exam) {
            $exam['trang_thai'] = match ($exam['trang_thai']) {
                'da_nop' => 'Đã nộp',
                'dang_lam' => 'Đang làm',
                'chua_lam' => 'Chưa làm',
                'gian_doan' => 'Gián đoạn',
                default => 'Không xác định'
            };
        }
    
        $this->view('layouts/main_layout.php', 
                    [// layout partials
                        'sidebar' => 'giaovien/partials/menu.php',
                        'content' => 'giaovien/pages/xem-baikt-hs.php'
                    ],
                    [// data
                        'student_exam'=> $result,
                        'student_info' => [
                            'id' => $userInfo['id'],
                            'ho_ten' => $userInfo['ho_ten'],
                            'email' => $userInfo['email'],
                            'anh' => $userInfo['anh']
                        ],
                        'averageScore' => $this->hocSinhThiModel
                                                ->getAveragePointByStudentAndClass(
                                                    $_SESSION['class_id'],
                                                    $userInfo['id']
                                                    )['diem_tb_hs'] ?? null,
                        'CSS_FILE' => [
                            'public/css/giaovien.css',
                            'public/css/gv-xem-bai-kt.css'
                        ]
                        ]);

    }

    public function statistics() : void {
        $this->view('layouts/main_layout.php', 
                    [// layout partials
                        'sidebar' => 'giaovien/partials/menu.php',
                        'content' => 'giaovien/pages/thong-ke.php'
                    ],
                    [// data
                        'CSS_FILE' => [
                            'public/css/giaovien.css',
                            'public/css/gv-xem-bai-kt.css'
                        ]
                        ]);
    }

    public function update(int $class_id) : void {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type, Accept');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ten_lop = $_POST['ten_lop'] ?? '';
            $mo_ta = $_POST['mo_ta'] ?? '';
            $user_id = $_SESSION['user_id'];
            $user_role = $_SESSION['user_role'];

            // Chỉ giáo viên mới được cập nhật lớp học
            if ($user_role !== 'gv') {
                return_json(['success' => false, 'message' => 'Bạn không có quyền cập nhật lớp học.']);
            }

            // Validate input
            if (empty($ten_lop)) {
                return_json(['success' => false, 'message' => 'Tên lớp không được để trống.']);
            }

            // Kiểm tra quyền sở hữu lớp học
            $class = $this->lopHocModel->getById($class_id);
            if (!$class || $class['gv_id'] != $user_id) {
                return_json(['success' => false, 'message' => 'Bạn không có quyền sửa lớp học này.']);
            }

            $result = $this->lopHocModel->update($class_id, [
                'ten_lop' => $ten_lop,
                'mo_ta' => $mo_ta
            ]);

            if ($result) {
                return_json(['success' => true, 'message' => 'Cập nhật lớp học thành công.']);
            } else {
                return_json(['success' => false, 'message' => 'Cập nhật lớp học thất bại.']);
            }
        } else {
            return_json(['success' => false, 'message' => 'Phương thức không hợp lệ.']);
        }
    }

    public function delete($class_id) : void{
        $result = $this->lopHocModel->clearAll($class_id);
        if (!$result['success']) {
            echo "<script>alert('Xóa lớp học thất bại! {$result['message']}');</script>";
        }
        navigate('/teacher/home');
    }

    public function out_class(int $class_id): void {
        
        $class_id = $class_id;
        $student_id = $_SESSION['user_id'];

        $result = $this->hocSinhLopModel->thoat_sach($class_id, $student_id);
        if (!$result['success'])
            echo "<script>alert('thoát lớp học thất bại! {$result['message']}');</script>";
        navigate('/student/home');
    }
}