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
    
    public function getExamByStudent(int $hs_id) : ?array {
        $sql = "SELECT 
            dt.id AS de_thi_id,
            dt.tieu_de,
            dt.ngay_thi,
            dt.ngay_dong,
            dt.tg_phut,
            dt.mo_ta,
            lh.ten_lop,
            CASE 
                WHEN NOW() > dt.ngay_dong THEN 1 ELSE 0
                END AS qua_han
        FROM DeThi dt
        JOIN LopHoc lh ON dt.lh_id = lh.id
        JOIN HocSinhLop hsl ON lh.id = hsl.lh_id
        WHERE hsl.hs_id = ? -- <-- Truyền ID học sinh vào đây
        ORDER BY dt.ngay_thi DESC
        ";
        return $this->db->fetchAll($sql, [$hs_id]) ?? null;
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
        $sql = "INSERT INTO {$this->table} (nguoi_tao_id, lh_id, tieu_de, mo_ta, tg_phut, tong_diem, ngay_thi, ngay_dong) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $this->db->execute($sql, [
            $data['nguoi_tao_id'],
            $data['lh_id'],
            $data['tieu_de'],
            $data['mo_ta'] ?? null,
            $data['tg_phut'],
            $data['tong_diem'],
            $data['ngay_thi'],
            $data['ngay_khoa']
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

    /**
     * Lấy những đề thi trong lớp mà học sinh chưa tham gia
     */
    public function getUnattendedExamsByStudent(int $hs_id): array
    {
        $sql = "SELECT 
                dt.id as de_thi_id,
                dt.tieu_de,
                dt.mo_ta,
                dt.tg_phut,
                dt.tong_diem,
                dt.ngay_thi,
                dt.ngay_dong,
                dt.ngay_tao,
                lh.ten_lop,
                lh.ma_lop
            FROM DeThi dt
            INNER JOIN LopHoc lh ON dt.lh_id = lh.id
            LEFT JOIN HocSinhThi hst ON dt.id = hst.de_thi_id AND hst.hs_id = ? -- hs_id
            WHERE dt.ngay_dong > NOW() -- Kiểm tra đề thi chưa đóng
            AND hst.de_thi_id IS NULL;";

        return $this->db->fetchAll($sql, [$hs_id]) ?? [];
    }

    /**
     * Lấy bài kiểm tra quá hạn và đã tham gia của một học sinh
     */
    public function getExpiredAndAttendedExamsByStudent(int $hs_id): array
    {
        $sql = "SELECT 
                    dt.id,
                    dt.tieu_de,
                    dt.mo_ta,
                    dt.tg_phut,
                    dt.tong_diem,
                    dt.ngay_thi,
                    dt.ngay_dong,
                    dt.ngay_tao,
                    lh.ten_lop,
                    lh.ma_lop,
                    hst.diem,
                    hst.bat_dau,
                    hst.ket_thuc,
                    CASE 
                        WHEN hst.id IS NOT NULL THEN 'da_tham_gia'
                        WHEN dt.ngay_dong <= NOW() THEN 'qua_han'
                        ELSE 'khac'
                    END as trang_thai
                FROM DeThi dt
                INNER JOIN LopHoc lh ON dt.lh_id = lh.id
                LEFT JOIN HocSinhThi hst ON dt.id = hst.de_thi_id AND hst.hs_id = ?
                WHERE (
                    dt.ngay_dong <= NOW()
                    OR hst.id IS NOT NULL
                )
                ORDER BY 
                    CASE WHEN hst.id IS NOT NULL THEN 0 ELSE 1 END,
                    dt.ngay_thi DESC";
        return $this->db->fetchAll($sql, [$hs_id]) ?? [];
    }    
}
