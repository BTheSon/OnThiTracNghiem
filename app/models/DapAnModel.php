<?php
namespace App\Models;

use App\Core\Model;

class DapAnModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'DapAn';
    }
    
    /**
     * Lấy tất cả đáp án của câu hỏi
     */
    public function getByCauHoi(int $cauHoiId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE cau_hoi_id = ?";
        return $this->db->fetchAll($sql, [$cauHoiId]);
    }
    
    /**
     * Lấy đáp án đúng của câu hỏi
     */
    public function getCorrectAnswer(int $cauHoiId): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE cau_hoi_id = ? AND da_dung = true";
        return $this->db->fetch($sql, [$cauHoiId]);
    }
    
    /**
     * Lấy đáp án theo ID
     */
    public function getById(int $id): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Tạo đáp án mới
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO {$this->table} (cau_hoi_id, noi_dung, da_dung) VALUES (?, ?, ?)";
        $this->db->execute($sql, [
            $data['cau_hoi_id'],
            $data['noi_dung'],
            $data['da_dung'] ?? false
        ]);
        
        return (int)$this->db->fetch("SELECT LAST_INSERT_ID()")['LAST_INSERT_ID()'];
    }
    /**
     * tạo nhiều đáp án cùg lúc
     */
    public function multitpleCreate(array $answers): int
    {
        $sql = "INSERT INTO {$this->table} (cau_hoi_id, noi_dung, da_dung) VALUES ";
        $params = [];
        $values = [];
        
        foreach ($answers as $answer) {
            $values[] = "(?, ?, ?)";
            $params[] = $answer['cau_hoi_id'];
            $params[] = $answer['noi_dung'];
            $params[] = isset($answer['da_dung']) ? (int)$answer['da_dung'] : 0; // Ép kiểu thành số nguyên
        }
        
        $sql .= implode(', ', $values);
        return $this->db->execute($sql, $params);
    }

    /**
     * Cập nhật đáp án
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
     * Xóa đáp án
     */
    public function delete(int $id): int
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
}