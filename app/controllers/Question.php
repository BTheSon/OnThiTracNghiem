<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\CauHoiModel;
use App\Models\DapAnModel;
use ArrayIterator;
use RuntimeException;

use function App\Includes\navigate;
use function App\Includes\return_json;

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
        // Kiểm tra quyền truy cập
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'gv') {
            navigate('auth/login');
            exit();
        }
        // Lấy danh sách câu hỏi của giáo viên
        $cauHoiList = $this->cauHoiModel->getByUser($_SESSION['user_id']);
        // // lấy danh sách đáp án của các câu hỏi
        $dapAnList = [];
        foreach ($cauHoiList as $cauHoi) {
            $dapAnList[$cauHoi['id']] = $this->dapAnModel->getByCauHoi($cauHoi['id']);
        }

        // Truyền dữ liệu vào view
        $this->view('layouts/quanly_layout.php',[
            'sidebar' => 'giaovien/partials/menu.php',
            'content' => 'giaovien/pages/list-cau-hoi.php',
            'navbar' => 'giaovien/partials/quanly_navbar.php'
        ],[
            'cau_hoi_list' => $cauHoiList,
            'dap_an_list' => $dapAnList,
            'CSS_FILE' => ['public/css/giaovien.css']

        ]);
    }

    public function form_create(){
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
                'do_kho' => $do_kho, // Môn học của câu hỏi
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
    public function load_question() {
        // / Kiểm tra file upload
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type, Accept');

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["fileUpload"])) {
            $file = $_FILES["fileUpload"];
            
            // Kiểm tra lỗi upload
            if ($file["error"] !== UPLOAD_ERR_OK) {
                return_json(['status' => 'error', 'message' => 'Lỗi khi upload file: ' . $file["error"]]);
            }

            // Kiểm tra loại file
            if ($file["type"] !== 'text/csv') {
                return_json(['status' => 'error', 'message' => 'Chỉ chấp nhận file CSV']);
            }

            // Đường dẫn file tạm
            $filePath = $file["tmp_name"];
            
            // Đọc file CSV
            try {
                $data = $this->readCSV($filePath);
            } catch(RuntimeException $e) {
                return_json(['status' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()]);
            }
            
            //headers = ["nội dung", "đáp án a", "đáp án b", "đáp án c", "đáp án d", "đáp án đúng", "độ khó"]
            try {
                foreach($data as $row) {
                    $noi_dung = $row['nội dung'];
                    $dapan_a = $row['đáp án a'];
                    $dapan_b = $row['đáp án b'];
                    $dapan_c = $row['đáp án c'];
                    $dapan_d = $row['đáp án d'];
                    $dapan_dung = $row['đáp án đúng'];
                    $do_kho = $row['độ khó'];
                    
                    $cauHoiData = [
                        'nguoi_tao_id' => $_SESSION['user_id'],
                        'do_kho' => $do_kho,
                        'noi_dung' => $noi_dung
                    ];
                    $id_cau_hoi = $this->cauHoiModel->create($cauHoiData);
    
                    $answers = [
                        ['cau_hoi_id' => $id_cau_hoi, 'noi_dung' => $dapan_a, 'da_dung' => ($dapan_dung === 'a')],
                        ['cau_hoi_id' => $id_cau_hoi, 'noi_dung' => $dapan_b, 'da_dung' => ($dapan_dung === 'b')],
                        ['cau_hoi_id' => $id_cau_hoi, 'noi_dung' => $dapan_c, 'da_dung' => ($dapan_dung === 'c')],
                        ['cau_hoi_id' => $id_cau_hoi, 'noi_dung' => $dapan_d, 'da_dung' => ($dapan_dung === 'd')],
                    ];
                    $this->dapAnModel->multitpleCreate($answers);
                }
            } catch (RuntimeException $e) {
                return_json(['status' => 'error', 'message' => $e->getMessage()]);
            }

            // Xóa file tạm
            unlink($filePath);

            // Trả về dữ liệu
            return_json(['status' => 'success', 'message' => 'tải file câu hỏi thành công', 'data' => $data]);
        }
        return_json( ['status' => 'error', 'message' => 'Không có file được upload']);
    }
    private function readCSV(string $filePath): array {
        $data = [];

        // Kiểm tra file tồn tại và có thể đọc
        if (!file_exists($filePath)) {
            throw new RuntimeException("File không tồn tại: $filePath");
        }
        if (!is_readable($filePath)) {
            throw new RuntimeException("Không có quyền đọc file: $filePath");
        }

        if (($handle = fopen($filePath, "r")) === false) {
            throw new RuntimeException("Không thể mở file: $filePath");
        }

        // Đọc header
        $headers = fgetcsv($handle, 1000, ",");
        if ($headers === false || count($headers) === 0) {
            fclose($handle);
            throw new RuntimeException("File CSV bị lỗi, không đọc được header");
        }

        // Kiểm tra header có trùng lặp cột không
        if (count($headers) !== count(array_unique($headers))) {
            fclose($handle);
            throw new RuntimeException("File CSV có cột header trùng lặp");
        }
        $expectedHeaders = ["nội dung", "đáp án a", "đáp án b", "đáp án c", "đáp án d", "đáp án đúng", "độ khó"];
        if ($headers !== $expectedHeaders) {
            fclose($handle);
            throw new RuntimeException("Header của file CSV không đúng định dạng. Yêu cầu: " . implode(", ", $expectedHeaders));
        }

        while (($row = fgetcsv($handle, 1000, ",")) !== false) {

            // Nếu số cột không khớp với header
            if (count($row) !== count($headers)) {
                continue; // Bỏ qua dòng bị lỗi
            }

            $assoc = array_combine($headers, $row);
            if ($assoc === false) {
                continue;
            }
            
            // chuyển thành chữ thường, loại bỏ khoảng trắng đầu cuối
            $assoc['đáp án đúng'] = mb_strtolower(trim($assoc['đáp án đúng']), 'UTF-8');
            // kiểm tra chỉ cho phép a, b, c, d
            if (!in_array($assoc['đáp án đúng'], ['a', 'b', 'c', 'd'])) {
                fclose($handle);
                throw new RuntimeException("Giá trị cột 'đáp án đúng' không hợp lệ: " . $assoc['đáp án đúng'] . ". Chỉ chấp nhận a, b, c, d.");
            }
        
            $data[] = $assoc;
        }

        fclose($handle);
        return $data;
    }

}