<?php
namespace App\Models;

use App\Core\Model;
use RuntimeException;

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
        $sql = "SELECT hsl.*, 
                lh.ma_lop, 
                lh.ten_lop, 
                lh.mo_ta, 
                gv.ho_ten AS ten_gv
            FROM {$this->table} hsl
            INNER JOIN LopHoc lh ON hsl.lh_id = lh.id
            INNER JOIN NguoiDung gv ON lh.gv_id = gv.id
            WHERE hsl.lh_id = ? AND hsl.hs_id = ?
            LIMIT 1";
        return $this->db->fetch($sql, [$lopHocId, $studentId]) ?? null;
    }
    
    /**
     * Lấy danh sách lớp học của học sinh
     */
    public function getClassesByStudent(int $studentId): array
    {
        $sql = "SELECT hsl.*, lh.ma_lop, lh.ten_lop, lh.mo_ta, gv.ho_ten as ten_gv
                FROM {$this->table} hsl
                JOIN LopHoc lh ON hsl.lh_id = lh.id
                INNER JOIN NguoiDung gv ON lh.gv_id = gv.id
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
     * Học sinh thoát lớp học và xóa tất cả dữ liệu liên quan
     * @return array
     * - success: true nếu thành công, false nếu thất bại
     * - message: thông báo kết quả
     * - data: dữ liệu trả về (nếu có)
     */
    function thoat_sach(int $hs_id, int $lop_id): array {
        $db = $this->db;
        
        try {
            // Kiểm tra học sinh có trong lớp không
            $kiemTra = $db->fetch(
                "SELECT id FROM HocSinhLop WHERE hs_id = ? AND lh_id = ?", 
                [$hs_id, $lop_id]
            );
            
            if (!$kiemTra) {
                return [
                    'success' => false,
                    'message' => 'Học sinh không có trong lớp học này',
                    'data' => null
                ];
            }
            
            // Bắt đầu transaction
            $db->query("START TRANSACTION");
            
            // 1. Xóa trả lời bài thi của học sinh trong lớp này
            $sql1 = "DELETE tlbt FROM TraLoiBaiThi tlbt
                    INNER JOIN HocSinhThi hst ON tlbt.hs_thi_id = hst.id
                    INNER JOIN DeThi dt ON hst.de_thi_id = dt.id
                    WHERE hst.hs_id = ? AND dt.lh_id = ?";
            $db->execute($sql1, [$hs_id, $lop_id]);
            
            // 2. Xóa bài thi của học sinh trong lớp này
            $sql2 = "DELETE hst FROM HocSinhThi hst
                    INNER JOIN DeThi dt ON hst.de_thi_id = dt.id
                    WHERE hst.hs_id = ? AND dt.lh_id = ?";
            $db->execute($sql2, [$hs_id, $lop_id]);
            
            // 3. Xóa học sinh khỏi lớp
            $result = $db->execute(
                "DELETE FROM HocSinhLop WHERE hs_id = ? AND lh_id = ?", 
                [$hs_id, $lop_id]
            );
            
            if ($result === 0) {
                throw new RuntimeException("Không thể xóa học sinh khỏi lớp");
            }
            
            // Commit transaction
            $db->query("COMMIT");
            
            return [
                'success' => true,
                'message' => 'Học sinh đã thoát lớp thành công!',
                'data' => [
                    'hs_id' => $hs_id,
                    'lop_id' => $lop_id
                ]
            ];
            
        } catch (RuntimeException $e) {
            // Rollback nếu có lỗi
            $db->query("ROLLBACK");
            return [
                'success' => false,
                'message' => 'Lỗi khi thoát lớp: ' . $e->getMessage(),
                'data' => null
            ];
        }
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