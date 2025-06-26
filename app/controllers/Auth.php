<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\NguoiDungModel;

use function App\Includes\navigate;

/**
 * Auth Controller
 * Nhận diện và xử lý các yêu cầu liên quan đến xác thực người dùng.
 * Bao gồm đăng nhập, đăng ký, đổi mật khẩu và đăng xuất.
 */
class Auth extends Controller
{
    private NguoiDungModel $model;

    public function __construct()
    {
        $this->model = $this->model('NguoiDungModel');
    }
    public function index() {
        $this->login();
        header('Location: '. BASE_URL .'/auth/login');
    }
    public function login(): void {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validate input
            if (empty($email) || empty($password)) {
                $this->view('auth/login', [], ['error' => 'Cần phải nhập email và mật khẩu.']);
                return;
            }
            // Check user credentials
            $user = $this->model->getByEmail($email);
            if ($user && password_verify($password, $user['mk'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['ho_ten'];
                $_SESSION['user_role'] = $user['vai_tro'];
                $_SESSION['user_email'] = $email;
                $_SESSION['user_avt_url'] = $user['anh'];

                // Redirect to dashboard
                if ($user['vai_tro'] == 'hs') {
                    navigate('/student');
                } elseif ($user['vai_tro'] == 'gv') {
                    navigate('/teacher');
                } else {
                    echo "<script>alert('không tìm thấy vai trò');</script>";
                }
                exit();
            } else {
                $this->view('auth/dangnhap.php',[], ['error' => 'Email hoặc mật khẩu không đúng.']);
                return;
            }
        } else {
            $this->view('auth/dangnhap.php');
        }
    }

    public function register(): void{
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ho_ten = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'] == 'hocsinh' ? 'hs' : 'gv';

            // Validate input
            if (empty($ho_ten) || empty($email) || empty($password)) {
                $this->view('auth/dangky.php',[], ['error' => 'All fields are required.']);
                return;
            }

            // Check if email already exists
            if ($this->model->getByEmail($email)) {
                $this->view('auth/dangky.php',[], ['error' => 'Email already exists.']);
                return;
            }

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Create new user
            $userId = $this->model->create([
                'ho_ten' => $ho_ten,
                'email' => $email,
                'mk' => $hashedPassword,
                'vai_tro' => $role,
                'anh' => DEFAULT_AVT_URL
            ]);

            // Set session variables
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $ho_ten;
            $_SESSION['user_role'] = $role;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_avt_url'] = DEFAULT_AVT_URL;
            // Redirect to dashboard
            echo "<script>alert('Đăng ký thành công!');</script>";
            navigate('/auth/login');
        } else {
            $this->view('auth/dangky.php');
        }
    }
    
    public function change_password(){
        $userId = $_SESSION['user_id'] ?? null;
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $userId) {
            $oldPassword = $_POST['old_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            $user = $this->model->getById($userId);

            if (!$user || !password_verify($oldPassword, $user['mk'])) {
                $message = 'Mật khẩu cũ không đúng!';
            } elseif ($newPassword !== $confirmPassword) {
                $message = 'Mật khẩu mới không khớp!';
            } else {
                $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                $this->model->update($userId, ['mk' => $hash]);
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


    public function logout(): void {
        // Destroy the session
        session_destroy();
        // Redirect to login page
        navigate('/auth/login');
    }
}