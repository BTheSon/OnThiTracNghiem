<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\LopHocModel;
use App\Models\HocSinhLopModel;
use App\Models\HocSinhThiModel;
use App\Models\NguoiDungModel;

use function App\Includes\navigate;
use function App\Includes\return_json;

/**
 * Controller xử lý các chức năng liên quan đến lớp học
 * Bao gồm: tạo lớp, tham gia lớp, xem thống kê, cập nhật và xóa lớp
 */
class Classroom extends Controller
{
    // Khai báo các model cần thiết
    private LopHocModel $lopHocModel;           // Model quản lý lớp học
    private HocSinhLopModel $hocSinhLopModel;   // Model quản lý học sinh trong lớp
    private HocSinhThiModel $hocSinhThiModel;   // Model quản lý bài thi của học sinh
    private NguoiDungModel $nguoiDungModel;     // Model quản lý người dùng

    /**
     * Constructor - Khởi tạo các model và kiểm tra xác thực người dùng
     */
    public function __construct() {
        // Khởi tạo các model
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
                navigate('/auth/login');
                exit();
            }
        }
    }

    /**
     * Tạo lớp học mới (chỉ dành cho giáo viên)
     * Phương thức AJAX - trả về JSON response
     */
    public function create(): void {
        // Thiết lập các header cho phản hồi AJAX
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *'); // Cho phép tất cả domain
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type, Accept'); 
        
        // Chỉ xử lý POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $ten_lop = $_POST['tenlop'];
            $ma_lop = $_POST['malop'];
            $id_gv = $_SESSION['user_id'];
            
            // Kiểm tra quyền - chỉ giáo viên mới được tạo lớp
            if ($_SESSION['user_role'] !== 'gv') {
                echo json_encode(['success' => false, 'message' => 'Bạn không có quyền tạo lớp học.']);
                return;
            }
            
            // Validate dữ liệu đầu vào
            if (empty($ten_lop) || empty($ma_lop)) {
                echo json_encode(['success' => false, 'message' => 'Cần phải nhập tên lớp và mã lớp.']);
                return;
            }

            // Kiểm tra mã lớp đã tồn tại chưa
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
            
            // Xử lý kết quả
            if ($result > 0) {
                echo json_encode(['success' => true, 'message' => 'Lớp học đã được tạo thành công.']);
            } elseif ($result === 0) {
                echo json_encode(['success' => false, 'message' => 'Lớp học đã tồn tại.']);
            } else {
                echo json_encode(['success' => false, 'message'=>'Có lỗi xảy ra trong quá trình tạo lớp học.']);
            }
            exit();
        } else {
            // Phương thức không hợp lệ
            echo json_encode(['success' => false, 'message'=>'Có lỗi xảy ra trong quá trình tạo lớp học.']);
            exit();
        }
    }

    /**
     * Tham gia lớp học (chỉ dành cho học sinh)
     * Phương thức AJAX - trả về JSON response
     */
    public function join() : void {
        // Thiết lập các header cho phản hồi AJAX
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type, Accept'); 
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $ma_lop = $_POST['malop'];
            $id_hs = $_SESSION['user_id'];
            
            // Kiểm tra quyền - chỉ học sinh mới được tham gia lớp
            if ($_SESSION['user_role'] !== 'hs') {
                echo json_encode(['success' => false, 'message' => 'Bạn không có quyền tham gia lớp học.']);
                return;
            }

            // Validate dữ liệu đầu vào
            if (empty($ma_lop)) {
                echo json_encode(['success' => false, 'message' => 'Cần phải nhập mã lớp.']);
                return;
            }

            // Lấy ID lớp từ mã lớp
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
            
            // Thêm học sinh vào lớp
            $result = $this->hocSinhLopModel->addStudentToClass($idLop, $id_hs);
            if ($result > 0) {
                echo json_encode(['success' => true, 'message' => 'Tham gia lớp học thành công.', ['id' => $result]]);
            } else{
                echo json_encode(['success' => false, 'message'=>'Có lỗi xảy ra trong quá trình tham gia lớp học.']);
            }
            exit();
        } else {
            // Phương thức không hợp lệ
            echo json_encode(['success' => false, 'message'=>'Có lỗi xảy ra trong quá trình tham gia lớp học.']);
            exit();
        }
    }

    /**
     * Xem danh sách bài kiểm tra của một học sinh cụ thể
     * Chỉ giáo viên mới có quyền xem
     * 
     * @param string $student_id ID của học sinh cần xem
     */
    public function view_student_exams (string $student_id): void {
        $id_gv = $_SESSION['user_id'];

        // Lấy danh sách kết quả thi của học sinh
        $result = $this->hocSinhThiModel->getResultsByStudent($student_id);
        
        // Lấy thông tin học sinh
        $userInfo = $this->nguoiDungModel->getById($student_id);
        
        // Chuyển đổi trạng thái thành dạng dễ đọc
        foreach ($result as &$exam) {
            $exam['trang_thai'] = match ($exam['trang_thai']) {
                'da_nop' => 'Đã nộp',
                'dang_lam' => 'Đang làm',
                'chua_lam' => 'Chưa làm',
                'gian_doan' => 'Gián đoạn',
                default => 'Không xác định'
            };
        }
    
        // Render view với dữ liệu
        $this->view('layouts/main_layout.php', 
                    [// Layout partials
                        'sidebar' => 'giaovien/partials/menu.php',
                        'content' => 'giaovien/pages/xem-baikt-hs.php'
                    ],
                    [// Dữ liệu truyền vào view
                        'student_exam'=> $result,
                        'student_info' => [
                            'id' => $userInfo['id'],
                            'ho_ten' => $userInfo['ho_ten'],
                            'email' => $userInfo['email'],
                            'anh' => $userInfo['anh']
                        ],
                        // Tính điểm trung bình của học sinh trong lớp
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

    /**
     * Hiển thị trang thống kê
     * Chỉ dành cho giáo viên
     */
    public function statistics() : void {
        $this->view('layouts/main_layout.php', 
                    [// Layout partials
                        'sidebar' => 'giaovien/partials/menu.php',
                        'content' => 'giaovien/pages/thong-ke.php'
                    ],
                    [// Dữ liệu truyền vào view
                        'CSS_FILE' => [
                            'public/css/giaovien.css',
                            'public/css/gv-xem-bai-kt.css'
                        ]
                        ]);
    }

    /**
     * Cập nhật thông tin lớp học
     * Chỉ giáo viên sở hữu lớp mới được cập nhật
     * 
     * @param int $class_id ID của lớp học cần cập nhật
     */
    public function update(int $class_id) : void {
        // Thiết lập header cho AJAX response
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type, Accept');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $ten_lop = $_POST['ten_lop'] ?? '';
            $mo_ta = $_POST['mo_ta'] ?? '';
            $user_id = $_SESSION['user_id'];
            $user_role = $_SESSION['user_role'];

            // Kiểm tra quyền - chỉ giáo viên mới được cập nhật
            if ($user_role !== 'gv') {
                return_json(['success' => false, 'message' => 'Bạn không có quyền cập nhật lớp học.']);
            }

            // Validate dữ liệu đầu vào
            if (empty($ten_lop)) {
                return_json(['success' => false, 'message' => 'Tên lớp không được để trống.']);
            }

            // Kiểm tra quyền sở hữu lớp học
            $class = $this->lopHocModel->getById($class_id);
            if (!$class || $class['gv_id'] != $user_id) {
                return_json(['success' => false, 'message' => 'Bạn không có quyền sửa lớp học này.']);
            }

            // Thực hiện cập nhật
            $result = $this->lopHocModel->update($class_id, [
                'ten_lop' => $ten_lop,
                'mo_ta' => $mo_ta
            ]);

            // Trả về kết quả
            if ($result) {
                return_json(['success' => true, 'message' => 'Cập nhật lớp học thành công.']);
            } else {
                return_json(['success' => false, 'message' => 'Cập nhật lớp học thất bại.']);
            }
        } else {
            return_json(['success' => false, 'message' => 'Phương thức không hợp lệ.']);
        }
    }

    /**
     * Xóa lớp học và tất cả dữ liệu liên quan
     * Chỉ giáo viên sở hữu lớp mới được xóa
     * 
     * @param mixed $class_id ID của lớp học cần xóa
     */
    public function delete($class_id) : void{
        // Xóa toàn bộ dữ liệu của lớp (bao gồm học sinh, bài thi, v.v.)
        $result = $this->lopHocModel->clearAll($class_id);
        
        // Hiển thị thông báo nếu xóa thất bại
        if (!$result['success']) {
            echo "<script>alert('Xóa lớp học thất bại! {$result['message']}');</script>";
        }
        
        // Chuyển hướng về trang chủ giáo viên
        navigate('/teacher/home');
    }

    /**
     * Học sinh thoát khỏi lớp học
     * Chỉ học sinh trong lớp mới được thoát
     * 
     * @param int $class_id ID của lớp học cần thoát
     */
    public function out_class(int $class_id): void {
        $student_id = $_SESSION['user_id'];

        // Thực hiện thoát lớp
        $result = $this->hocSinhLopModel->thoat_sach($class_id, $student_id);
        
        // Hiển thị thông báo nếu thoát thất bại
        if (!$result['success'])
            echo "<script>alert('thoát lớp học thất bại! {$result['message']}');</script>";
        
        // Chuyển hướng về trang chủ học sinh
        navigate('/student/home');
    }
}