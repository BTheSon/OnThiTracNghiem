<?php
namespace App\Models;

use App\Core\Model;


class TraLoiBaiThiModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'TraLoiBaiThi';
    }
    
    /**
     * Lấy tất cả câu trả lời của học sinh trong bài thi
     */
    public function getAnswersByStudentExam(int $hocSinhThiId): array
    {
        $sql = "SELECT tlbt.*, ch.noi_dung as cau_hoi_noi_dung, da.noi_dung as dap_an_noi_dung
                FROM {$this->table} tlbt
                JOIN CauHoi ch ON tlbt.cau_hoi_id = ch.id
                JOIN DapAn da ON tlbt.da_id = da.id
                WHERE tlbt.hs_thi_id = ?";
        return $this->db->fetchAll($sql, [$hocSinhThiId]);
    }
    
    /**
     * Lưu câu trả lời của học sinh
     */
    public function createAnswer(array $data): int
    {
        $sql = "INSERT INTO {$this->table} (hs_thi_id, cau_hoi_id, da_id, dung) VALUES (?, ?, ?, ?)";
        $this->db->execute($sql, [
            $data['hs_thi_id'],
            $data['cau_hoi_id'],
            $data['da_id'],
            $data['dung'] ?? false
        ]);
        
        return (int)$this->db->fetch("SELECT LAST_INSERT_ID()")['LAST_INSERT_ID()'];
    }

    /**
     * Lưu câu trả lời từ bảng tạm vào bảng chính
     */
    public function saveAnswersByTemp($hst_id): int
    {
        $sql = "INSERT INTO TraLoiBaiThi (hs_thi_id, cau_hoi_id, da_id, dung)
                SELECT 
                    tlt.hs_thi_id,
                    tlt.cau_hoi_id,
                    tlt.da_id,
                    da.da_dung AS dung
                FROM TraLoiTam tlt
                JOIN DapAn da ON tlt.da_id = da.id;
                WHERE tlt.hs_thi_id = ?";
        return $this->db->execute($sql, [$hst_id]);
    }
    
    /**
     * Xóa câu trả lời
     */
    public function delete(int $id): int
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    /**
     * Cập nhật câu trả lời
     */
    public function updateAnswer(int $id, int $dapAnId, bool $dung): int
    {
        $sql = "UPDATE {$this->table} SET da_id = ?, dung = ? WHERE id = ?";
        return $this->db->execute($sql, [$dapAnId, $dung, $id]);
    }
    
    /**
     * Kiểm tra xem học sinh đã trả lời câu hỏi chưa
     */
    public function hasAnswered(int $hocSinhThiId, int $cauHoiId): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE hs_thi_id = ? AND cau_hoi_id = ?";
        $result = $this->db->fetch($sql, [$hocSinhThiId, $cauHoiId]);
        return $result['count'] > 0;
    }
    
    /**
     * Lấy câu trả lời bởi ID
     */
    public function getById(int $id): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Đếm số câu trả lời đúng trong bài thi
     */
    public function countCorrectAnswers(int $hocSinhThiId): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE hs_thi_id = ? AND dung = true";
        $result = $this->db->fetch($sql, [$hocSinhThiId]);
        return (int)$result['count'];
    }
}