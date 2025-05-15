<?php
namespace App\Models;

use App\Core\Model;

class LopHocModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'LopHoc';
    }
    
    /**
     * Lấy tất cả lớp học
     */
    public function getAll(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Lấy lớp học theo ID
     */
    public function getById(int $id): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Lấy lớp học theo mã lớp
     */
    public function getByMaLop(string $maLop): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE ma_lop = ?";
        return $this->db->fetch($sql, [$maLop]);
    }
    
    /**
     * Lấy lớp học của giáo viên
     */
    public function getByTeacherId(int $teacherId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE gv_id = ?";
        return $this->db->fetchAll($sql, [$teacherId]);
    }
    
    /**
     * Tạo lớp học mới
     * @pram array $data = [gv_id, ma_lop, ten_lop, mo_ta??]
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO {$this->table} (gv_id, ma_lop, ten_lop, mo_ta) VALUES (?, ?, ?, ?)";
        $this->db->execute($sql, [
            $data['gv_id'], 
            $data['ma_lop'], 
            $data['ten_lop'], 
            $data['mo_ta'] ?? null
        ]);
        
        return (int)$this->db->fetch("SELECT LAST_INSERT_ID()")['LAST_INSERT_ID()'];
    }
    
    /**
     * Cập nhật thông tin lớp học
     * @pram array $data = [gv_id, ma_lop, ten_lop, mo_ta]
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
     * Xóa lớp học
     */
    public function delete(int $id): int
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    /**
     * Kiểm tra mã lớp đã tồn tại chưa
     */
    public function maLopExists(string $maLop): bool
    {
        /**
         * Kiểm tra mã lớp đã tồn tại trong bảng lớp học
         * Sử dụng EXISTS để kiểm tra sự tồn tại của bản ghi
         * Nếu tồn tại, trả về true, ngược lại false
         * và lưu vào trong trường found 
         */
        $sql = "SELECT EXISTS(SELECT 1 FROM {$this->table} WHERE ma_lop = ?) AS found";
        $result = $this->db->fetch($sql, [$maLop]);
        return (bool) $result['found'];
    }
}