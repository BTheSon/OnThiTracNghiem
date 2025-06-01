<?php
namespace App\Models;

use App\Core\Model;
use RuntimeException;

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
    
    public function getIdByMaLop(string $maLop): int
    {
        $sql = "SELECT id FROM {$this->table} WHERE ma_lop = ?";
        $result = $this->db->fetch($sql, [$maLop]);
        if ($result === false || !isset($result['id'])) {
            return 0;
        }
        return (int)$result['id'];
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

    /** 
     * xóa sạch lớp học
     */
    function clearAll(int $lop_id): array {
        $db = $this->db;
        
        try {
            // Bắt đầu transaction
            $db->query("START TRANSACTION");
            
            // 1. Xóa trả lời bài thi của học sinh trong các đề thi của lớp
            $sql1 = "DELETE tlbt FROM TraLoiBaiThi tlbt
                    INNER JOIN HocSinhThi hst ON tlbt.hs_thi_id = hst.id
                    INNER JOIN DeThi dt ON hst.de_thi_id = dt.id
                    WHERE dt.lh_id = ?";
            $db->execute($sql1, [$lop_id]);
            
            // 2. Xóa bài thi của học sinh trong các đề thi của lớp
            $sql2 = "DELETE hst FROM HocSinhThi hst
                    INNER JOIN DeThi dt ON hst.de_thi_id = dt.id
                    WHERE dt.lh_id = ?";
            $db->execute($sql2, [$lop_id]);
            
            // 3. Xóa câu hỏi trong đề thi của lớp
            $sql3 = "DELETE chdt FROM CauHoiDeThi chdt
                    INNER JOIN DeThi dt ON chdt.de_id = dt.id
                    WHERE dt.lh_id = ?";
            $db->execute($sql3, [$lop_id]);
            
            // 4. Xóa đề thi của lớp
            $db->execute("DELETE FROM DeThi WHERE lh_id = ?", [$lop_id]);
            
            // 5. Xóa tài liệu của lớp
            $db->execute("DELETE FROM TaiLieu WHERE lh_id = ?", [$lop_id]);
            
            // 6. Xóa thông báo của lớp
            $db->execute("DELETE FROM ThongBao WHERE lh_id = ?", [$lop_id]);
            
            // 7. Xóa học sinh khỏi lớp
            $db->execute("DELETE FROM HocSinhLop WHERE lh_id = ?", [$lop_id]);
            
            // 8. Cuối cùng xóa lớp học
            $result = $db->execute("DELETE FROM LopHoc WHERE id = ?", [$lop_id]);
            
            if ($result === 0) {
                throw new RuntimeException("Không tìm thấy lớp học với ID: $lop_id");
            }
            
            // Commit transaction
            $db->query("COMMIT");
            
            return [
                'success' => true,
                'message' => 'Xóa lớp học thành công!',
                'data' => ['lop_id' => $lop_id]
            ];
            
        } catch (RuntimeException $e) {
            // Rollback nếu có lỗi
            $db->query("ROLLBACK");
            return [
                'success' => false,
                'message' => 'Lỗi khi xóa lớp học: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }
}