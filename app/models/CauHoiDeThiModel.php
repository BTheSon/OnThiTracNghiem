<?php
namespace App\Models;

use App\Core\Model;

class CauHoiDeThiModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'CauHoiDeThi';
    }    
    /**
     * Lấy danh sách câu hỏi của đề thi
     */
    public function getQuestionsByExam(int $deThiId): array
    {
        $sql = "SELECT chdt.*, ch.noi_dung, ch.do_kho, ch.hinh, ch.am_thanh, ch.cong_thuc, ch.id as ch_id
                FROM {$this->table} chdt
                JOIN CauHoi ch ON chdt.cau_id = ch.id
                WHERE chdt.de_id = ?";
        return $this->db->fetchAll($sql, [$deThiId]);
    }
    
    /**
     * Thêm câu hỏi vào đề thi
     */
    public function addQuestionToExam(int $deThiId, int $cauHoiId): int
    {
        $sql = "INSERT INTO {$this->table} (de_id, cau_id) VALUES (?, ?)";
        $this->db->execute($sql, [$deThiId, $cauHoiId]);
        
        return (int)$this->db->fetch("SELECT LAST_INSERT_ID()")['LAST_INSERT_ID()'];
    }
    
    /**
     * Xóa câu hỏi khỏi đề thi
     */
    public function removeQuestionFromExam(int $deThiId, int $cauHoiId): int
    {
        $sql = "DELETE FROM {$this->table} WHERE de_id = ? AND cau_id = ?";
        return $this->db->execute($sql, [$deThiId, $cauHoiId]);
    }

    public function removeAllFromExam(int $deThiId): int {
        $sql = "DELETE FROM {$this->table} WHERE de_id = ?";
        return $this->db->execute($sql, [$deThiId]);
    }
    
    /**
     * Đếm số lượng câu hỏi trong đề thi
     */
    public function countQuestions(int $deThiId): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE de_id = ?";
        $result = $this->db->fetch($sql, [$deThiId]);
        return (int)$result['count'];
    }

    public function countCorrectQuestion(array $answers): int {
        if (empty($answers)) return 0;
    
        $unionParts = [];
        $params = [];
        
        foreach ($answers as $cauHoiId => $dapAnId) {
            $unionParts[] = "
                SELECT 1 as found
                FROM CauHoiDeThi chdt
                JOIN CauHoi ch ON chdt.cau_id = ch.id
                JOIN DapAn da ON da.cau_hoi_id = ch.id
                WHERE chdt.id = ? AND da.id = ? AND da.da_dung = 1
            ";
            $params[] = $cauHoiId;
            $params[] = $dapAnId;
        }
        
        $unionQuery = implode(' UNION ALL ', $unionParts);
        $sql = "SELECT COUNT(*) as so_dap_an_dung FROM ($unionQuery) subquery";
        $t = $this->db->fetch($sql, $params)['so_dap_an_dung'];
        return (int)$t ?? 0;
    }
}
