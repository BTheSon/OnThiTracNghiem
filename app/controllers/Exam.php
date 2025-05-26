<?php
namespace App\Controllers;

use App\Core\Controller;
use function App\Includes\navigate;
class Exam extends Controller
{
    public function form_create()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'gv') {
            navigate('/auth/login');
            exit();
        }

        if (!isset($_SESSION['class_id'])) {
            navigate('/teacher/home');
            exit();
        }

        $this->view('', [
                'content' => 'giaovien/pages/tao-bai-thi.php',
            ], [
                'JS_FILE' => ['public/js/form-create-exam.js']
            ]
        );
    }
}