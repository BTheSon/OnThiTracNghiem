<?php
namespace App\Models;

use App\Core\Model;

class HocSinhLopModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'HocSinhLop';
    }    
    /**
     * Lấy danh sách học sinh trong lớp
     */
    public function getStudentsByClass(int $lopHocId): array
    {
        $sql = "SELECT hsl.*, nd.ho_ten, nd.email, nd.anh 
                FROM {$this->table} hsl
                JOIN NguoiDung nd ON hsl.hs_id = nd.id
                WHERE hsl.lh_id = ?";
        return $this->db->fetchAll($sql, [$lopHocId]);
    }

    /**
     * lấy danh sách học sinh trong lớp theo tên
     */
    public function getStudentsByClassAndName(int $lopHocId, string $name): array    {
        $sql = "SELECT hsl.*, nd.ho_ten, nd.email, nd.anh 
                FROM {$this->table} hsl
                JOIN NguoiDung nd ON hsl.hs_id = nd.id
                WHERE hsl.lh_id = ? AND nd.ho_ten LIKE ?";
        return $this->db->fetchAll($sql, [$lopHocId, '%' . $name . '%']);
    }

    /**
     * Lấy thông tin lớp học của học sinh theo id lớp và id học sinh
     */
    public function getClassInfoByClassAndStudent(int $lopHocId, int $studentId): ?array
    {
        $sql = "SELECT 
                SELECT hsl.*, 
                lh.ma_lop, 
                lh.ten_lop, 
                lh.mo_ta, 
                gv.ho_ten AS ten_gv
            FROM {$this->table} hsl
            INNER JOIN LopHoc lh ON hsl.lh_id = lh.id
            INNER JOIN NguoiDung gv ON lh.gv_id = gv.id
            WHERE hsl.lh_id = ? AND hsl.hs_id = ?
            LIMIT 1";
        return $this->db->fetch($sql, [$lopHocId, $studentId]);
    }
    
    /**
     * Lấy danh sách lớp học của học sinh
     */
    public function getClassesByStudent(int $studentId): array
    {
        $sql = "SELECT hsl.*, lh.ma_lop, lh.ten_lop, lh.mo_ta
                FROM {$this->table} hsl
                JOIN LopHoc lh ON hsl.lh_id = lh.id
                WHERE hsl.hs_id = ?";
        return $this->db->fetchAll($sql, [$studentId]);
    }
    
    /**
     * Thêm học sinh vào lớp
     */
    public function addStudentToClass(int $lopHocId, int $studentId): int
    {
        $sql = "INSERT INTO {$this->table} (lh_id, hs_id) VALUES (?, ?)";
        $this->db->execute($sql, [$lopHocId, $studentId]);
        
        return (int)$this->db->fetch("SELECT LAST_INSERT_ID()")['LAST_INSERT_ID()'];
    }
    
    /**
     * Xóa học sinh khỏi lớp
     */
    public function removeStudentFromClass(int $lopHocId, int $studentId): int
    {
        $sql = "DELETE FROM {$this->table} WHERE lh_id = ? AND hs_id = ?";
        return $this->db->execute($sql, [$lopHocId, $studentId]);
    }
    
    /**
     * Kiểm tra học sinh đã tham gia lớp chưa
     */
    public function isStudentInClass(int $lopHocId, int $studentId): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE lh_id = ? AND hs_id = ?";
        $result = $this->db->fetch($sql, [$lopHocId, $studentId]);
        return $result['count'] > 0;
    }
}