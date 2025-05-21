<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\NguoiDungModel;

use function App\Includes\navigate;
use function App\Includes\redirect;

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
            ]);

            // Set session variables
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $ho_ten;
            $_SESSION['user_role'] = $role;

            // Redirect to dashboard
            echo "<script>alert('Đăng ký thành công!');</script>";
            navigate('/auth/login');
        } else {
            $this->view('auth/dangky.php');
        }
    }
}