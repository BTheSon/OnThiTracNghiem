<?php
namespace App\Controllers;

use App\Core\Controller;

class Question extends Controller
{
    public function index()
    {
        // Load the view for managing questions
        $this->view('giaovien/pages/quan-ly-cau-hoi');
    }
    /**
     * hiển thị form thêm câu hỏi vào ngân hàng
     */
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