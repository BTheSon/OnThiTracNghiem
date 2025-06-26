<?php
namespace App\Core;

/**
 * Model class
 * Cơ sở dữ liệu kết nối và thao tác với bảng trong ứng dụng.
 */
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