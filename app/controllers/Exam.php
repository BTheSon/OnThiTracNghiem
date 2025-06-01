<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\CauHoiDeThiModel;
use App\Models\CauHoiModel;
use App\Models\DapAnModel;
use App\Models\DeThiModel;
use App\Models\HocSinhThiModel;
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
    }

    /**
     * hiển thị danh sách các bài thi của tạo bởi giáo viên
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
            
            if (!$examId) {
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

    public function update(): void {
        
    }

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
     * làm bài thi
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
     * lấy câu hỏi trong bài thi bài thi với examid trong session
     */
    public function questions(): void {
        // xác thực người dùng
        if (empty($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hs') {
            navigate('/auth/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dethi_id = $_SESSION['exam_info']['dethi_id'];
            
            $questions = $this->cauHoiDeThiModel->getQuestionsByExam($dethi_id);
            $dethi = $this->deThiModel->getById($_SESSION['exam_info']['dethi_id']);
            
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

            header('Content-Type: application/json; charset=utf-8');
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: POST');
            header('Access-Control-Allow-Headers: Content-Type, Accept'); 
            
            echo json_encode($data);
            exit();
        } else {
            throw new Exception('phải là phương thức post');
        }
    } 
    public function submit() {
        
        // Kiểm tra phương thức request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
            exit();
        }

        // Kiểm tra thông tin bài thi trong session
        if (empty($_SESSION['exam_info']['hst_id']) || empty($_SESSION['exam_info']['dethi_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Exam session not found.']);
            exit();
        }

        // Lấy dữ liệu gửi lên
        $input = json_decode($_POST['questions'], true);
        if (!is_array($input) || empty($input)) {
            echo json_encode(['status' => 'error', 'message' => 'No answers submitted.']);
            exit();
        }

        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *'); // Cho phép tất cả domain (có thể giới hạn domain cụ thể)
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type, Accept'); 

        $finalPoint = $this->calculatePoint($input);
        echo json_encode(['status' => 'success', 'message' => "u got {$finalPoint} point yaa", 'finalPoint' => $finalPoint]);
        // xử lí lưu vào hocsinhthi
        $hst_id = $_SESSION['exam_info']['hst_id'];
        $this->hocSinhThiModel->updateResult($hst_id, $finalPoint);
        exit();
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
