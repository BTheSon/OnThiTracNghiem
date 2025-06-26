<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\CauHoiDeThiModel;
use App\Models\CauHoiModel;
use App\Models\DapAnModel;
use App\Models\DeThiModel;
use App\Models\HocSinhThiModel;
use App\Models\TraLoiBaiThiModel;
use App\Models\TraLoiTamModel;
use Exception;

use function App\Includes\navigate;
use function App\Includes\return_json;

class Exam extends Controller
{
    private CauHoiModel $cauHoiModel;
    private DeThiModel $deThiModel;
    private CauHoiDeThiModel $cauHoiDeThiModel;
    private HocSinhThiModel $hocSinhThiModel;
    private DapAnModel $dapAnModel;
    private TraLoiTamModel $traLoiTamModel;
    private TraLoiBaiThiModel $traLoiBaiThiModel;

    /**
     * Khởi tạo các mô hình cần thiết và kiểm tra quyền truy cập
     */
    public function __construct() {
        if (empty($_SESSION['user_id'])) {
            navigate('/auth/login');
            exit();
        }
        $this->cauHoiModel = new CauHoiModel();
        $this->deThiModel = new DeThiModel();
        $this->cauHoiDeThiModel = new CauHoiDeThiModel();
        $this->hocSinhThiModel = new HocSinhThiModel();
        $this->dapAnModel = new DapAnModel();
        $this->traLoiTamModel = new TraLoiTamModel();
        $this->traLoiBaiThiModel = new TraLoiBaiThiModel();
    }

    /**
     * hiển thị danh sách các bài thi của tạo bởi giáo viên
     * Chỉ dành cho giáo viên.
     * response: hiển thị danh sách bài thi
     */
    public function list() {
        if (empty($_SESSION['user_role']) || $_SESSION['user_role'] !== 'gv') 
            navigate('auth/login');

        $data = $this->deThiModel->getByCreator((int)$_SESSION['user_id']);
        $this->view('layouts/quanly_layout.php', 
                [
                    'sidebar' => 'giaovien/partials/menu.php',
                    'navbar' => 'giaovien/partials/quanly_navbar.php',
                    'content' => 'giaovien/pages/quanly_baikt.php'
                ],
                [
                    'exams' => $data,
                    'CSS_FILE' => [
                        'public/css/giaovien.css',
                        'public/css/quanly-baikt.css'
                    ],
                    'JS_FILE' => [
                        'public/js/quanly_baikt.js'
                    ]
                ]);
        
    }

    /**
     * Hiển thị trang tạo bài thi.
     * Chỉ dành cho giáo viên.
     * response: hiển thị form tạo bài thi
     */
    public function form_create(): void{

        if (!isset($_SESSION['class_id'])) {
            navigate('/teacher/home');
            exit();
        }
        
        if (empty($_SESSION['user_role']) || $_SESSION['user_role'] !== 'gv') {
            navigate('/teacher/home');
            exit();
        }
        
        $questions = $this->cauHoiModel->getByUser($_SESSION['user_id']);
        $this->view('', [
                'content' => 'giaovien/pages/tao-bai-thi.php',
            ], [
                'JS_FILE' => ['public/js/form-create-exam.js'],
                'questions' => $questions
            ]
        );
    }

    /**
     * Xử lý tạo bài thi.
     * Chỉ dành cho giáo viên.
     * response: tạo bài thi mới
     */
    public function create(): void {
        if (!isset($_SESSION['class_id'])) {
            navigate('/teacher/home');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $examTitle       = trim($_POST['exam_title'] ?? '');
            $examDescription = trim($_POST['exam_description'] ?? '');
            $examStartTime   = trim($_POST['exam_start_time'] ?? '');
            $examLockTime    = trim($_POST['exam_lock_time'] ?? '');
            $examDuration    = intval($_POST['exam_duration'] ?? 0);
            $questionIds     = $_POST['question_ids'] ?? [];

            // Validate required fields
            if (!$examTitle || !$examStartTime || $examDuration <= 0) {
                echo "Missing or invalid required fields.";
                exit();
            }
            // lưu vào cơ sở dữ liệu
            $examData = [
                'nguoi_tao_id' => $_SESSION['user_id'],
                'lh_id' => $_SESSION['class_id'],
                'tieu_de' => $examTitle,
                'mo_ta' => $examDescription,
                'ngay_thi' => $examStartTime,
                'ngay_khoa' => $examLockTime,
                'tong_diem' => 10, // Tổng điểm sẽ được tính sau khi thêm câu hỏi
                'tg_phut' => $examDuration,
            ];

            // tạo bài thi
            $examId = $this->deThiModel->create($examData);
            $hsthi = $this->hocSinhThiModel->addAllStudentsToExam($examId, $_SESSION['class_id']);

            if (empty($examId) || $examId === 0) {
                echo '<script>alert("Failed to create exam. Please try again.");</script>';
                navigate('/teacher/home');
                exit();
            }
            // Lưu các câu hỏi liên kết với bài thi
            foreach ($questionIds as $questionId) {
                $this->cauHoiDeThiModel->addQuestionToExam((int)$examId, (int)$questionId);
            }

            echo "<script>
                alert('Exam created successfully!');
            </script>";
            navigate("/teacher/class-management/{$_SESSION['class_id']}");
        } else {
            navigate('/teacher/home');
            exit();
        }
    }

    // Idk why it's here :))
    public function update(): void {
        
    }

    /**
     * Xóa bài thi theo ID.
     * Chỉ dành cho giáo viên.
     * response: xóa bài thi thành công hoặc thất bại
     */
    public function delete($dethi_id): void {
        if ($_SESSION['user_role'] !== 'gv') {
            navigate('/teacher/home');
        }

        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type, Accept'); 
            

        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            if ($this->hocSinhThiModel->countHsJoinExam($dethi_id) > 0) {
                return_json([
                    'success' => false,
                    'message' => 'đã có học sinh tham gia bài thi, không được phép xóa'
                ]);
            }

            $this->cauHoiDeThiModel->removeAllFromExam($dethi_id);
            $numRowDelete = $this->deThiModel->delete($dethi_id);

            return_json([
                'success' => true,
                'message' => 'xóa đề thi thành công'
            ]);
        } else {
            return_json([
                'success' => false,
                'message' => 'sai phương thức gửi dữ liệu'
            ]);
        }


    }

    /**
     * Bắt đầu làm bài thi cho học sinh.
     * Chỉ dành cho học sinh.
     * response: hiển thị trang làm bài thi
     */
    public function on_exam(int $dethi_id) {
        if (empty($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hs') {
            navigate('/auth/login');
        }
        // id 
        $idExamHS = $this->hocSinhThiModel->create([
            'de_thi_id' => $dethi_id,
            'hs_id' => $_SESSION['user_id']
        ]);

        if ($idExamHS === 0) {
            throw new Exception('Học sinh thi không thể tạo');
        }

        $dethi_id = $this->hocSinhThiModel->getIdDeThiById($idExamHS);
        if ($dethi_id === 0) {
            throw new Exception('Không tìm thấy id đề theo id học sinh thi: ' . $idExamHS );
        }

        $_SESSION['exam_info'] = [
            'hst_id' => $idExamHS,
            'dethi_id' => $dethi_id
        ];

        $this->view('layouts/lambai_layout.php',[
                        'content' => 'hocsinh/pages/lam-bai.php'
                    ],[
                        'CSS_FILE' => ['public/css/hs-lam-bai-kt.css'],
                        'JS_FILE' => ['public/js/lam-bai.js'],
                    ]);
    }

    /**
     * Lấy danh sách câu hỏi của bài thi hiện tại.
     * Chỉ dành cho học sinh.
     * response: JSON chứa danh sách câu hỏi và thông tin bài thi
     */
    public function questions(): void {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type, Accept'); 
            
        // xác thực người dùng
        if (empty($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hs') {
            return_json(["status" => "error", "message" => "Unauthorized"]);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dethi_id = $_SESSION['exam_info']['dethi_id'];
            
            try {
                $questions = $this->cauHoiDeThiModel->getQuestionsByExam($dethi_id);
                $dethi = $this->deThiModel->getById($_SESSION['exam_info']['dethi_id']);
            }catch (Exception $e) {
                return_json(["status" => "error", "message" => $e->getMessage()]);
            }
            
            $data = [
                "status" => "success",
                "data" => [
                    "hst_id" => $_SESSION['exam_info']['hst_id'] ?? null,
                    "tieu_de" => $dethi['tieu_de'] ?? null,
                    "tg_phut" => $dethi['tg_phut'] ?? null,
                    "bat_dau" => $dethi['ngay_thi'] ?? null,
                    "ket_thuc" => $dethi['ngay_dong'] ?? null,
                    "cau_hoi" => array_map(function($q) {
                                    return [
                                        "id" => $q['id'],
                                        "noi_dung" => $q['noi_dung'],
                                        "hinh" => $q['hinh'] ?? null,
                                        "am_thanh" => $q['am_thanh'] ?? null,
                                        "cong_thuc" => $q['cong_thuc'] ?? null,
                                        "dap_an" => array_map(function($a) {
                                                        $answer = [
                                                            "id" => $a['id'],
                                                            "noi_dung" => $a['noi_dung']
                                                        ];
                                                        return $answer;
                                                    }, $this->dapAnModel->getByCauHoi($q['ch_id']))
                                    ];
                                }, $questions)
                ]
                ];

            echo json_encode($data);
            exit();
        } else {
            return_json(["status" => "error", "message" => "Invalid request method."]);
        }
    } 


    /**
     * Lưu trạng thái khi client gặp sự cố (ví dụ: mất kết nối, reload trang)
     * Client sẽ gọi API này để lưu lại trạng thái làm bài hiện tại
     */
    public function on_crash(): void {
        // Các header này không cần thiết khi dùng với sendBeacon, nhưng không gây lỗi nếu giữ lại
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type, Accept');

        // Kiểm tra phương thức request
        // sendBeacon thường gửi dữ liệu bằng phương thức POST, nhưng có thể không phải lúc nào cũng là POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return_json(['status' => 'error', 'message' => 'Invalid request method.']);
        }

        if (empty($_SESSION['exam_info']['hst_id'])) {
            return_json(['status' => 'error', 'message' => 'Exam session not found.']);
        }

        $hst_id = $_SESSION['exam_info']['hst_id'];

        try {
            $this->hocSinhThiModel->updateLastSave($hst_id);
            $this->hocSinhThiModel->updateState($hst_id, 'gian_doan');
            return_json(['status' => 'success', 'message' => 'Crash state saved.', 'time' => date('Y-m-d H:i:s')]);
        } catch (Exception $e) {
            return_json(['status' => 'error', 'message' => 'Failed to save crash state: ' . $e->getMessage()]);
        }
    }


    /**
     * lưu câu trả lời vào bảng tạm thời định kì
     */
    public function save() : void {
        header('Content-Type: application/json; charset=utf-8');
        header('Accept: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type, Accept');

        // Kiểm tra phương thức request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return_json(['status' => 'error', 'message' => 'Invalid request method.']);
        }

        // Kiểm tra thông tin bài thi trong session
        if (empty($_SESSION['exam_info']['hst_id']) || empty($_SESSION['exam_info']['dethi_id'])) {
            return_json(['status' => 'error', 'message' => 'Exam session not found.']);
        }
        
        $hst_id = $_SESSION['exam_info']['hst_id'];

        // Lấy dữ liệu JSON từ body
        $rawInput = file_get_contents('php://input');

        if (empty($rawInput)) {
            return_json(['status' => 'error', 'message' => 'No data received.']);
        }

        $input = json_decode($rawInput, true);

        if (!is_array($input) || empty($input)) {
            return_json(['status' => 'error', 'message' => 'No answers submitted.']);
        }

        $answers = $input['questions'] ?? [];
        if (empty($answers)) {
            return_json(['status' => 'error', 'message' => 'No answers submitted.']);
        }

        // Kiểm tra định dạng câu trả lời
        foreach ($answers as $questionId => $answerId) {
            if (!is_numeric($questionId) || !is_numeric($answerId)) {
                return_json(['status' => 'error', 'message' => 'Invalid answer format.']);
            }
        }

        // lưu câu trả lời tạm thời
        try {
            $this->hocSinhThiModel->updateLastSave($hst_id);
            $numLineChange = $this->traLoiTamModel->updateAnswers($hst_id, $answers);
        } catch (Exception $e) {
            return_json(['status' => 'error', 'message' => 'Failed to save answers: ' . $e->getMessage()]);
        }

        return_json(['status' => 'success', 'message' => 'Save successful.', 'count' => $numLineChange]);
    }

    /**
     * Nộp bài thi
     * lấy data trong bảng tạm traloitam để lưu vào bảng traloibaithi
     * tính điểm và lưu vào bảng hocsinhthi
     */
    public function submit() : void {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Accept: application/json');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type, Accept'); 

        // Kiểm tra phương thức request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return_json(['status' => 'error', 'message' => 'Invalid request method.']);
        }

        // Kiểm tra thông tin bài thi trong session
        if (empty($_SESSION['exam_info']['hst_id']) || empty($_SESSION['exam_info']['dethi_id'])) {
            return_json(['status' => 'error', 'message' => 'Exam session not found.']);
        }

        $rawInput = file_get_contents('php://input');

        if (empty($rawInput)) {
            return_json(['status' => 'error', 'message' => 'No data received.']);
        }

        $input = json_decode($rawInput, true);

        if (!is_array($input) || empty($input)) {
            return_json(['status' => 'error', 'message' => 'No data submitted.']);
        }
        
        
        $answers = $input['questions'] ?? [];
        if (empty($answers)) {
            return_json(['status' => 'error', 'message' => 'No answers submitted.']);
        }

        // Kiểm tra định dạng câu trả lời
        foreach ($answers as $questionId => $answerId) {
            if (!is_numeric($questionId) || !is_numeric($answerId)) {
                return_json(['status' => 'error', 'message' => 'Invalid answer format.']);
            }
        }

        $finalPoint = $this->calculatePoint($answers);
        // xử lí lưu vào hocsinhthi
        $hst_id = $_SESSION['exam_info']['hst_id'];
        if (empty($hst_id)) {
            return_json(['status' => 'error', 'message' => 'Exam session ID not found.']);
        }
        // xử lí ngoại lệ
        try {
            $this->hocSinhThiModel->updateResult($hst_id, $finalPoint);
            $this->hocSinhThiModel->updateState($hst_id, 'da_nop');
            $this->traLoiBaiThiModel->saveAnswersByTemp($hst_id);
        } catch (Exception $e) {
            return_json(['status' => 'error', 'message' => 'Failed to save answers: ' . $e->getMessage()]);
        }
        
        return_json(['status' => 'success', 'message' => "u got {$finalPoint} point yaa", 'finalPoint' => $finalPoint]);
    }

    private function calculatePoint(array $questions) :float  {
        $totalQuestion = $this->cauHoiDeThiModel->countQuestions((int)$_SESSION['exam_info']['dethi_id']);
        $countCorrectQuestion = $this->cauHoiDeThiModel->countCorrectQuestion($questions);
        return  (float)$countCorrectQuestion / $totalQuestion * 10.0;
    }
    
}


// Array
// (
//     [hst_id] => 16
//     [questions] => {"1":49,"2":47}
// )
