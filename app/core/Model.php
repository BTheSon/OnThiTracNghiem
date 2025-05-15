<?php
namespace App\Core;

class Model
{
    protected DB $db;
    protected string $table;

    public function __construct()
    {
        $this->connect();
    }

    private function connect() : void {
        $this->db = DB::getInstance();
    }
}