<?php
namespace App\Models;

use App\Core\Model;

class DeThiModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'DeThi';
    }    
    /**
     * Lấy tất cả đề thi của lớp học
     */
    public function getByClass(int $lopHocId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE lh_id = ? ORDER BY ngay_thi DESC";
        return $this->db->fetchAll($sql, [$lopHocId]);
    }
    
    /**
     * Lấy đề thi theo ID
     */
    public function getById(int $id): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Tạo đề thi mới
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO {$this->table} (nguoi_tao_id, lh_id, tieu_de, mo_ta, tg_phut, tong_diem, ngay_thi) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $this->db->execute($sql, [
            $data['nguoi_tao_id'],
            $data['lh_id'],
            $data['tieu_de'],
            $data['mo_ta'] ?? null,
            $data['tg_phut'],
            $data['tong_diem'],
            $data['ngay_thi']
        ]);
        
        return (int)$this->db->fetch("SELECT LAST_INSERT_ID()")['LAST_INSERT_ID()'];
    }
    
    /**
     * Cập nhật đề thi
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
     * Xóa đề thi
     */
    public function delete(int $id): int
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    /**
     * Lấy tất cả đề thi của người tạo
     */
    public function getByCreator(int $creatorId): array
    {
        $sql = "SELECT dt.*, lh.ten_lop 
                FROM {$this->table} dt
                JOIN LopHoc lh ON dt.lh_id = lh.id
                WHERE dt.nguoi_tao_id = ? 
                ORDER BY dt.ngay_thi DESC";
        return $this->db->fetchAll($sql, [$creatorId]);
    }
}
