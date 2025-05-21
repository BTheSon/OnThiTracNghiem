<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\LopHocModel;

use function App\Includes\navigate;

class Classroom extends Controller
{
    private LopHocModel $model;
    public function __construct() {
        $this->model = $this->model('LopHocModel');
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'gv') {
            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
            echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập hoặc không có quyền.']);
            exit();
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

            // Validate input
            if (empty($ten_lop) || empty($ma_lop)) {
                echo json_encode(['success' => false, 'message' => 'Cần phải nhập tên lớp và mã lớp.']);
                return;
            }

            if ($this->model->maLopExists($ma_lop)) {
                echo json_encode(['success' => false, 'message'=>'đã tồn tại mã lớp này.']);
                return;
            }
            // Tạo lớp học mới
            $result = $this->model->create([
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
    
}