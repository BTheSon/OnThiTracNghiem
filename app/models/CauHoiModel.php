<?php
namespace App\Models;

use App\Core\Model;

class CauHoiModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'CauHoi';
    }
    
    /**
     * Lấy tất cả câu hỏi của người dùng
     */
    public function getByUser(int $userId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE nguoi_tao_id = ? ORDER BY ngay_tao DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }
    
    /**
     * Lấy câu hỏi theo ID
     */
    public function getById(int $id): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Tạo câu hỏi mới
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO {$this->table} (nguoi_tao_id, do_kho, noi_dung, hinh, am_thanh, cong_thuc) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $this->db->execute($sql, [
            $data['nguoi_tao_id'],
            $data['do_kho'],
            $data['noi_dung'],
            $data['hinh'] ?? null,
            $data['am_thanh'] ?? null,
            $data['cong_thuc'] ?? null
        ]);
        
        return (int)$this->db->fetch("SELECT LAST_INSERT_ID()")['LAST_INSERT_ID()'];
    }
    
    /**
     * Cập nhật câu hỏi
     */
    public function update(int $id, array $data): int
    {
        $fieldsToUpdate = [];
        $params = [];
        
        foreach ($data as $field => $value) {
            $fieldsToUpdate[] = "$field = ?";
            $params[] = $value;
        }
        
        $params[] = $id;
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fieldsToUpdate) . " WHERE id = ?";
        return $this->db->execute($sql, $params);
    }
    
    /**
     * Xóa câu hỏi
     */
    public function delete(int $id): int
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    /**
     * Tìm kiếm câu hỏi theo từ khóa
     */
    public function search(string $keyword): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE noi_dung LIKE ? ORDER BY ngay_tao DESC";
        return $this->db->fetchAll($sql, ["%$keyword%"]);
    }
}