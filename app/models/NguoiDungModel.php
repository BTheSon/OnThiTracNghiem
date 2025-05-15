<?php
namespace App\Models;

use App\Core\Model;

class NguoiDungModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'NguoiDung';
    }
    /**
     * Lấy tất cả người dùng
     */
    public function getAll(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Lấy người dùng theo ID
     */
    public function getById(int $id): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Lấy người dùng theo email
     */
    public function getByEmail(string $email): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        return $this->db->fetch($sql, [$email]);
    }
    
    /**
     * Tạo người dùng mới
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO {$this->table} (ho_ten, email, mk, vai_tro, anh) VALUES (?, ?, ?, ?, ?)";
        $this->db->execute($sql, [
            $data['ho_ten'], 
            $data['email'], 
            $data['mk'], 
            $data['vai_tro'], 
            $data['anh'] ?? null
        ]);
        
        // Trả về ID vừa thêm
        return (int)$this->db->fetch("SELECT LAST_INSERT_ID()")['LAST_INSERT_ID()'];
    }
    
    /**
     * Cập nhật thông tin người dùng
     */
    public function update(int $id, array $data): int
    {
        $fieldsToUpdate = [];
        $params = [];
        
        foreach ($data as $field => $value) {
            $fieldsToUpdate[] = "{$field} = ?";
            $params[] = $value;
        }
        
        $params[] = $id;
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fieldsToUpdate) . " WHERE id = ?";
        return $this->db->execute($sql, $params);
    }
    
    /**
     * Xóa người dùng
     */
    public function delete(int $id): int
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    /**
     * Kiểm tra đăng nhập
     */
    public function login(string $email, string $password): mixed
    {
        $user = $this->getByEmail($email);
        
        if ($user && password_verify($password, $user['mk'])) {
            return $user;
        }
        
        return false;
    }
    
    /**
     * Lấy danh sách giáo viên
     */
    public function getTeachers(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE vai_tro = 'gv'";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Lấy danh sách học sinh
     */
    public function getStudents(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE vai_tro = 'hs'";
        return $this->db->fetchAll($sql);
    }
}