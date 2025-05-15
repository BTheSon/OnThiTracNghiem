<?php
namespace App\Models;

use App\Core\Model;

class TaiLieuModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'TaiLieu';
    }
    
    /**
     * Lấy tất cả tài liệu của lớp học
     */
    public function getByClass(int $lopHocId): array
    {
        $sql = "SELECT tl.*, nd.ho_ten as nguoi_dang
                FROM {$this->table} tl
                JOIN NguoiDung nd ON tl.nguoi_dang_id = nd.id
                WHERE tl.lh_id = ?
                ORDER BY tl.ngay_dang DESC";
        return $this->db->fetchAll($sql, [$lopHocId]);
    }
    
    /**
     * Lấy tài liệu theo ID
     */
    public function getById(int $id): mixed
    {
        $sql = "SELECT tl.*, nd.ho_ten as nguoi_dang
                FROM {$this->table} tl
                JOIN NguoiDung nd ON tl.nguoi_dang_id = nd.id
                WHERE tl.id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Tạo tài liệu mới
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO {$this->table} (nguoi_dang_id, lh_id, tieu_de, mo_ta, file_dir) VALUES (?, ?, ?, ?, ?)";
        $this->db->execute($sql, [
            $data['nguoi_dang_id'],
            $data['lh_id'],
            $data['tieu_de'],
            $data['mo_ta'] ?? null,
            $data['file_dir']
        ]);
        
        return (int)$this->db->fetch("SELECT LAST_INSERT_ID()")['LAST_INSERT_ID()'];
    }
    
    /**
     * Cập nhật tài liệu
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
     * Xóa tài liệu
     */
    public function delete(int $id): int
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
}
