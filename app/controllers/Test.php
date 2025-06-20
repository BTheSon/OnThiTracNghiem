<?php
namespace App\Controllers;
use App\Core\Controller;

class Test extends Controller
{
    public function __construct() {
        
    }

    public function index() { 
        $this->view('tests/index.php');
    }
}