<?php
namespace App\Models;

use App\Core\Model;

class ThongBaoModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'ThongBao';
    }
    
    /**
     * Lấy tất cả thông báo của lớp học
     */
    public function getByClass(int $lopHocId): array
    {
        $sql = "SELECT tb.*, nd.ho_ten as nguoi_gui
                FROM {$this->table} tb
                JOIN NguoiDung nd ON tb.nguoi_gui_id = nd.id
                WHERE tb.lh_id = ?
                ORDER BY tb.ngay_tao DESC";
        return $this->db->fetchAll($sql, [$lopHocId]);
    }
    
    /**
     * Lấy thông báo theo ID
     */
    public function getById(int $id): mixed
    {
        $sql = "SELECT tb.*, nd.ho_ten as nguoi_gui
                FROM {$this->table} tb
                JOIN NguoiDung nd ON tb.nguoi_gui_id = nd.id
                WHERE tb.id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Tạo thông báo mới
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO {$this->table} (lh_id, nguoi_gui_id, tieu_de, noi_dung) VALUES (?, ?, ?, ?)";
        $this->db->execute($sql, [
            $data['lh_id'],
            $data['nguoi_gui_id'],
            $data['tieu_de'],
            $data['noi_dung']
        ]);
        
        return (int)$this->db->fetch("SELECT LAST_INSERT_ID()")['LAST_INSERT_ID()'];
    }
    
    /**
     * Cập nhật thông báo
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
     * Xóa thông báo
     */
    public function delete(int $id): int
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
}
