<?php
namespace App\Controllers;

use App\Core\Controller;

class Question extends Controller
{
    public function index() {
        // Load the view for managing questions
        $this->list();
    }
    /**
     * hiển thị form thêm câu hỏi vào ngân hàng
     */

    public function list() {
        $this->view('layouts/main_layout.php',[
            'sidebar' => 'giaovien/partials/menu.php',
            'content' => 'giaovien/pages/list-cau-hoi.php',
        ]);
    }

    public function form_create()
    {
        $this->view('',[
            'content' => 'giaovien/pages/tao-cau-hoi.php'
        ]);
    }

    public function edit($id)
    {
        // Logic to edit an existing question by ID
        // return view('giaovien/pages/sua-cau-hoi', ['id' => $id]);
    }
}