<?php
namespace App\Models;
use App\Core\Model;
class TraLoiTamModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'TraLoiTam';
    }
    /**
     * Lấy danh sách câu trả lời tạm thời của người dùng
     */
    public function getListByUserIdAndExamId(int $userId, int $examId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? AND de_id = ?";
        return $this->db->fetchAll($sql, [$userId, $examId]);
    }

    /**
     * Cập nhật danh sách câu trả lời tạm
     */
    public function updateAnswers(int $hs_thi_id, array $answers): int {
        if (empty($answers)) {
            return 0; // Không có câu trả lời nào để cập nhật
        }
        // $answers có dạng ['cauhoi_id' => 'dapan_id', ...]
        $placeholders = [];
        $params = [];
        foreach ($answers as $cau_hoi_id => $da_id) {
            $placeholders[] = '(?, ?, ?)';
            $params[] = $hs_thi_id;
            $params[] = $cau_hoi_id;
            $params[] = $da_id;
        }
        $sql = "INSERT INTO {$this->table} (hs_thi_id, cau_hoi_id, da_id) 
            VALUES " . implode(', ', $placeholders) . "
            ON DUPLICATE KEY UPDATE da_id = VALUES(da_id)";
        return $this->db->execute($sql, $params);
    }
    
}