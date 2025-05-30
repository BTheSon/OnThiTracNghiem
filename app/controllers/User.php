<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\NguoiDungModel;

use function App\Includes\navigate;

class User extends Controller {
    private NguoiDungModel $nguoi_dung_model;

    public function __construct() {
        if (empty($_SESSION['user_id'])) {
            navigate('/auth/login');
        }
        $this->nguoi_dung_model = $this->model('NguoiDungModel');
    }
    
    public function change_name() {
        $userId = $_SESSION['user_id'] ?? null;
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $newName = trim($_POST['new_name'] ?? '');

            $result = $this->nguoi_dung_model->update($userId, ['ho_ten' => $newName]); 
            $_SESSION['user_name'] = $newName;
            $message = 'Đổi tên thành công!';
        }
        $this->view('layouts/user_motify_layout.php',[
            'content' => 'auth/doiten.php'
        ], [
            'message' => $message,
            'CSS_FILE' => ['public/css/main.css']
        ]);
    }
    
    public function change_avatar() {
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->upload_avt();

        }

        $this->view('layouts/user_motify_layout.php',[
            'content' => 'auth/doiAvt.php'
        ], [
            'message' => $message,
            'CSS_FILE' => ['public/css/uploadAvt.css']
        ]);
    }

    private function upload_avt() {
        // Thiết lập các header cho phản hồi
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type, Accept');

        // lấy file từ form
        $file = $_FILES['profilePicture'];
        
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        // kiểm tra xem file có hợp lệ không, định dạng file word, pdf, excel, hình ảnh
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($fileType, ALLOWED_FILE_TYPES)) {
            $uploadDir = BASE_STORAGE_DIR . '/avatars/';
            $newFileName = uniqid() . '.' . $fileType;
            $uploadFilePath = $uploadDir . $newFileName;

            // kiểm tra xem thư mục uploads đã tồn tại chưa
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            if (move_uploaded_file($fileTmpName, $uploadFilePath)) {
                // uploadFilePath là dường dẫn tương đối
                $uploadFilePath = RELATIVE_STORAGE_PATH . '/avatars/' . $newFileName;
                // lưu thông tin tài liệu vào cơ sở dữ liệu
                if ($this->nguoi_dung_model->update($_SESSION['user_id'], ['anh' => $uploadFilePath]) > 0) {
                    $_SESSION['user_avt_url'] = $uploadFilePath;
                    echo json_encode(['success' => true, 'message' => 'Ảnh đại diện đã được tải lên thành công.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu ảnh đại diện vào cơ sở dữ liệu.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Lỗi khi tải lên tệp tin.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Định dạng tệp không hợp lệ.']);
        }
        exit(); 
    }
}