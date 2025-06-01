<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\TaiLieuModel;

use function App\Includes\navigate;

class Document extends Controller
{
    private TaiLieuModel $documentModel;
    public function __construct() {
        $this->documentModel = new TaiLieuModel();
    }

    public function form() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'gv') {
            navigate('/auth/login');
            exit(); 
        }

        if (!isset($_SESSION['class_id'])) {
            navigate('/teacher/home');
            exit();
        }
        $this->view('', [
                'content' => 'giaovien/pages/them-tai-lieu.php',
            ],[
                'JS_FILE' =>['public/js/form-add-tl.js']
            ]
        );
    }

    public function upload(): void
    {
        // Thiết lập các header cho phản hồi
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type, Accept');
        // xác thực quyền truy cập
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'gv' || !isset($_SESSION['class_id'])) {
            // nếu người dùng không phải là giáo viên hoặc không có quyền truy cập vào lớp học  
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập vào tài liệu.']);
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // lấy file từ form
            $file = $_FILES['tai_lieu_file'];
            $classId = $_SESSION['class_id'];
            $tieude = $_POST['tieu_de'];
            $mota = $_POST['mo_ta'];
            
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            // kiểm tra xem file có hợp lệ không, định dạng file word, pdf, excel, hình ảnh
            // thêm file vào thư mục uploads
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($fileType, ALLOWED_FILE_TYPES)) {
                $uploadDir = BASE_STORAGE_DIR . '/documents/';
                $newFileName = uniqid() . '.' . $fileType;
                $uploadFilePath = $uploadDir . $newFileName;

                // kiểm tra xem thư mục uploads đã tồn tại chưa
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                if (move_uploaded_file($fileTmpName, $uploadFilePath)) {
                    // uploadFilePath là dường dẫn tương đối
                    $uploadFilePath = RELATIVE_STORAGE_PATH . '/documents/' . $newFileName;
                    // lưu thông tin tài liệu vào cơ sở dữ liệu
                    $data = [
                        'nguoi_dang_id' => $_SESSION['user_id'],
                        'lh_id' => $classId,
                        'tieu_de' => $tieude,
                        'mo_ta' => $mota,
                        'file_dir' => $uploadFilePath
                    ];
                    if ($this->documentModel->create($data)) {
                        echo json_encode(['success' => true, 'message' => 'Tài liệu đã được tải lên thành công.']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu thông tin tài liệu vào cơ sở dữ liệu.']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Lỗi khi tải lên tệp tin.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Định dạng tệp không hợp lệ.']);
            }


        } else {
            echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ.']);
        }
    }

    public function download(int $id): void
    {
        // Xác thực quyền truy cập
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'hs') {
            navigate('/auth/login');
            exit();
        }

        // Lấy thông tin tài liệu từ cơ sở dữ liệu
        $document = $this->documentModel->getById($id);
        if ($document) {
            $filePath = BASE_DIR . $document['file_dir'];
            $fileExt =pathinfo($filePath, PATHINFO_EXTENSION);
            $fileName = $document['tieu_de'] . '.' . $fileExt;

            if (file_exists($filePath)) {
                // Thiết lập header để tải file
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . trim($fileName, " ")  . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filePath));
                readfile($filePath);
                exit();
            } else {
                echo 'không tìm thấy file ở server';
                exit();
            }
        } else {
            echo 'Tài liệu không tồn tại.';
            exit();
        }
    }
    public function list(): void
    {
        // xác thực quyền truy cập
        if (!isset($_SESSION['user_id'])) {
            navigate('/auth/login');
            exit();
        }
        switch ($_SESSION['user_role']) {
            case 'gv':
                $this->teacher_list_documents();
                break;
            case 'hs':
                $this->student_list_documents();
                break;
            default:
                navigate('/auth/login');
                exit();
        }
    }

    // chưa test
    private function student_list_documents(): void
    {
        if (!isset($_SESSION['class_id'])) {
            navigate('/student/home');
            exit();
        }

        $documents = $this->documentModel->getByClass($_SESSION['class_id']);
        $this->view('',
            [
                'content' => 'hocvien/pages/xem-tai-lieu.php',
                'documents' => $documents,
            ],[
                'JS_FILE' => ['public/js/view-document.js']
            ]);
        
    }

    private function teacher_list_documents(): void
    {

        $documents = $this->documentModel->getByTeacherId($_SESSION['user_id']);
        $this->view('layouts/quanly_layout.php', 
                [
                    'sidebar' => 'giaovien/partials/menu.php',
                    'navbar' => 'giaovien/partials/quanly_navbar.php',
                    'content' => 'giaovien/pages/xem-tai-lieu.php'
                ],
                [
                    'documents' => $documents,
                    'CSS_FILE' => [
                        'public/css/giaovien.css',
                        'public/css/gv-xem-tai-lieu.css'
                    ]
                ]);
    }
};