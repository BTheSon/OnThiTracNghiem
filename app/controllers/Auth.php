<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\NguoiDungModel;

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
                $this->view('auth/login', ['error' => 'Email and password are required.']);
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
                header('Location: /dashboard');
                exit();
            } else {
                $this->view('auth/login', ['error' => 'Invalid email or password.']);
            }
        } else {
            $this->view('dangnhap.php');
        }
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ho_ten = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Validate input
            if (empty($ho_ten) || empty($email) || empty($password)) {
                $this->view('auth/register', ['error' => 'All fields are required.']);
                return;
            }

            // Check if email already exists
            if ($this->model->getByEmail($email)) {
                $this->view('auth/register', ['error' => 'Email already exists.']);
                return;
            }

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Create new user
            $userId = $this->model->create([
                'ho_ten' => $ho_ten,
                'email' => $email,
                'mk' => $hashedPassword,
                'vai_tro' => 'user'
            ]);

            // Set session variables
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $ho_ten;
            $_SESSION['user_role'] = 'user';

            // Redirect to dashboard
            header('Location: /dashboard');
            exit();
        } else {
            $this->view('auth/register');
        }
    }
}