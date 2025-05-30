<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\NguoiDungModel;

use function App\Includes\navigate;

class User extends Controller {
    private NguoiDungModel $nguoi_dung_model;

    public function __construct() {
        $this->nguoi_dung_model = $this->model('NguoiDungModel');
    }
    
    public function change_name() {
        $userId = $_SESSION['user_id'] ?? null;
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId) {

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

    public function change_password()
    {
        $userId = $_SESSION['user_id'] ?? null;
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId) {
            $oldPassword = $_POST['old_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            $user = $this->nguoi_dung_model->getById($userId);

            if (!$user || !password_verify($oldPassword, $user['mk'])) {
                $message = 'Mật khẩu cũ không đúng!';
            } elseif ($newPassword !== $confirmPassword) {
                $message = 'Mật khẩu mới không khớp!';
            } else {
                $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                $this->nguoi_dung_model->update($userId, ['mk' => $hash]);
                $message = 'Đổi mật khẩu thành công!';
            }
        } 

        $this->view('layouts/user_motify_layout.php',[
            'content' => 'auth/doimk.php'
        ], [
            'message' => $message,
            'CSS_FILE' => ['public/css/main.css']
        ]);
    }
}