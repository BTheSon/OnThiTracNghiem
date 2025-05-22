<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\TaiLieuModel;

class Document extends Controller
{
    private TaiLieuModel $documentModel;
    public function __construct()
    {
        // kiểm tra quyền truy cập
        if (!isset($_SESSION['user_id'])    || $_SESSION['user_role'] !== 'gv') {
            echo json_encode(['success' => false, 'message' => 'Bạn không có quyền truy cập vào tài liệu.']);
            exit();
        }
        $this->documentModel = new TaiLieuModel();
    }
    public function upload(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type, Accept');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
        } else {
            echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ.']);
        }
    }
}