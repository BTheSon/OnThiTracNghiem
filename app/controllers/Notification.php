<?php 
namespace App\Controllers;
use App\Core\Controller;
use App\Models\ThongBaoModel;

use function App\Includes\navigate;

/**
 * Notification Controller
 * Xử lý các yêu cầu liên quan đến thông báo.
 * Bao gồm tạo thông báo mới.
 */
class Notification extends Controller {
    private ThongBaoModel $thong_bao_model;
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            navigate('/auth/login');
        }
        $this->thong_bao_model = $this->model('ThongBaoModel');
    }
    
    /**
     * Hiển thị form tạo thông báo.
     * Chỉ dành cho giáo viên.
     * response: hiển thị form tạo thông báo
     */
    public function create(): void{

        if (empty($_SESSION['user_role'])) {
            navigate('/auth/login');
            return;
        }
        if (empty($_SESSION['class_id'])) {
            navigate('/teacher');
            return;
        }
        
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';

        $data = [
            'lh_id' => $_SESSION['class_id'],
            'nguoi_gui_id' => $_SESSION['user_id'],
            'tieu_de' => $title,
            'noi_dung' => $description
        ];
        if ($this->thong_bao_model->create($data)) {
            navigate("/teacher/class-management/{$_SESSION['class_id']}");
        } else {
            echo "<script>alert('Tạo thông báo thất bại. Vui lòng thử lại!'); window.history.back();</script>";
        }

    }

}