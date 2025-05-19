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
        $this->view('dashboard.php');
    }
}