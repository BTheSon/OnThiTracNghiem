<?php
namespace App\Controllers;
use App\Core\Controller;

class Dashboard extends Controller
{
    public function __construct()
    {
        
    }
    
    public function index(): void
    {
        $this->view('index.php');
    }
}