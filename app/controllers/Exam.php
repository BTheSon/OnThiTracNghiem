<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\CauHoiDeThiModel;
use App\Models\CauHoiModel;
use App\Models\DeThiModel;

use function App\Includes\navigate;
class Exam extends Controller
{
    private CauHoiModel $cauHoiModel;
    private DeThiModel $deThiModel;
    private CauHoiDeThiModel $cauHoiDeThiModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'gv') {
            navigate('/auth/login');
            exit();
        }
        $this->cauHoiModel = new CauHoiModel();
        $this->deThiModel = new DeThiModel();
        $this->cauHoiDeThiModel = new CauHoiDeThiModel();
    }
    public function form_create()
    {

        if (!isset($_SESSION['class_id'])) {
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

    public function create(){
        if (!isset($_SESSION['class_id'])) {
            navigate('/teacher/home');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $examTitle       = trim($_POST['exam_title'] ?? '');
            $examDescription = trim($_POST['exam_description'] ?? '');
            $examStartTime   = trim($_POST['exam_start_time'] ?? '');
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
        } else {
            navigate('/teacher/home');
            exit();
        }
    }
}