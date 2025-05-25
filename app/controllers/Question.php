<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\CauHoiModel;
use App\Models\DapAnModel;

use function App\Includes\navigate;

class Question extends Controller
{
    private CauHoiModel $cauHoiModel;
    private DapAnModel $dapAnModel;
    public function __construct() {
        // Kiểm tra quyền truy cập
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'gv') {
            navigate('auth/login');
            exit();
        }

        $this->cauHoiModel = $this->model('CauHoiModel');
        $this->dapAnModel = $this->model('DapAnModel');
    }
    public function index() {
        // Load the view for managing questions
        $this->list();
    }
    /**
     * hiển thị form thêm câu hỏi vào ngân hàng
     */

    public function list() {
        $this->view('layouts/main_layout.php',[
            'sidebar' => 'giaovien/partials/menu.php',
            'content' => 'giaovien/pages/list-cau-hoi.php',
        ]);
    }

    public function form_create()
    {
        $this->view('',[
            'content' => 'giaovien/pages/tao-cau-hoi.php'
        ]);
    }

    public function add() {
        // Kiểm tra xem form đã được submit chưa
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Lấy dữ liệu từ form và gán vào biến
            $do_kho = isset($_POST['do_kho']) ? trim($_POST['do_kho']) : '';
            $noi_dung = isset($_POST['noi_dung']) ? trim($_POST['noi_dung']) : '';
            $dap_an_1 = isset($_POST['dap_an_1']) ? trim($_POST['dap_an_1']) : '';
            $dap_an_2 = isset($_POST['dap_an_2']) ? trim($_POST['dap_an_2']) : '';
            $dap_an_3 = isset($_POST['dap_an_3']) ? trim($_POST['dap_an_3']) : '';
            $dap_an_4 = isset($_POST['dap_an_4']) ? trim($_POST['dap_an_4']) : ''; // Đây là đáp án đúng
            
            // tạo data
            $data = [
                'nguoi_tao_id' => $_SESSION['user_id'], // ID của người tạo câu hỏi
                'mon_hoc' => 'undefine', // Môn học của câu hỏi
                'noi_dung' => $noi_dung,
                'hinh' => null, // Nếu có hình ảnh thì sẽ xử lý sau
                'am_thanh' => null, // Nếu có âm thanh thì sẽ xử lý sau
                'cong_thuc' => null, // Nếu có công thức toán học thì sẽ xử lý sau
            ];
            $id_cau_hoi = $this->cauHoiModel->create($data);
            // Tạo mảng đáp án
            $answers = [
                ['cau_hoi_id' => $id_cau_hoi, 'noi_dung' => $dap_an_1, 'da_dung' => false],
                ['cau_hoi_id' => $id_cau_hoi, 'noi_dung' => $dap_an_2, 'da_dung' => false],
                ['cau_hoi_id' => $id_cau_hoi, 'noi_dung' => $dap_an_3, 'da_dung' => false],
                ['cau_hoi_id' => $id_cau_hoi, 'noi_dung' => $dap_an_4, 'da_dung' => true], // Đáp án đúng
            ];
            $this->dapAnModel->multitpleCreate($answers);

            navigate('/question/list');
        }
    }
}