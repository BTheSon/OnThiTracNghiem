<?php
namespace App\Models;

use App\Core\Model;

class HocSinhThiModel extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'HocSinhThi';
    }
    
    /**
     * Lấy kết quả thi của học sinh theo đề thi
     */
    public function getStudentExamResult(int $deThiId, int $studentId): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE de_thi_id = ? AND hs_id = ?";
        return $this->db->fetch($sql, [$deThiId, $studentId]);
    }
    
    /**
     * Lấy tất cả kết quả thi của đề thi
     */
    public function getResultsByExam(int $deThiId): array
    {
        $sql = "SELECT hst.*, nd.ho_ten
                FROM {$this->table} hst
                JOIN NguoiDung nd ON hst.hs_id = nd.id
                WHERE hst.de_thi_id = ?
                ORDER BY hst.diem DESC";
        return $this->db->fetchAll($sql, [$deThiId]);
    }
    
    /**
     * Lấy tất cả kết quả thi của học sinh
     */
    public function getResultsByStudent(int $studentId): array
    {
        $sql = "SELECT hst.*, dt.tieu_de, dt.tong_diem, dt.tg_phut, lh.ten_lop
                FROM {$this->table} hst
                JOIN DeThi dt ON hst.de_thi_id = dt.id
                JOIN LopHoc lh ON dt.lh_id = lh.id
                WHERE hst.hs_id = ?
                ORDER BY hst.ket_thuc DESC";
        return $this->db->fetchAll($sql, [$studentId]);
    }
    /**
     * lấy thông tin bài thi của học sinh theo id học sinh và id lớp học
     */
    public function getResultsByStudentAndClass(int $studentId, int $classId): array
    {
        $sql = "SELECT hst.*, dt.tieu_de, dt.tong_diem, dt.tg_phut, lh.ten_lop
                FROM {$this->table} hst
                JOIN DeThi dt ON hst.de_thi_id = dt.id
                JOIN LopHoc lh ON dt.lh_id = lh.id
                WHERE hst.hs_id = ? AND lh.id = ?
                ORDER BY hst.ket_thuc DESC";
        return $this->db->fetchAll($sql, [$studentId, $classId]);
    }
    
    /**
     * Tạo bản ghi học sinh làm bài thi
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO {$this->table} (de_thi_id, hs_id, bat_dau) VALUES (?, ?, ?)";
        $this->db->execute($sql, [
            $data['de_thi_id'],
            $data['hs_id'],
            $data['bat_dau'] ?? date('Y-m-d H:i:s')
        ]);
        
        return (int)$this->db->fetch("SELECT LAST_INSERT_ID()")['LAST_INSERT_ID()'];
    }
    
    /**
     * Cập nhật kết quả thi
     */
    public function updateResult(int $id, float $diem, string $ketThuc = null): int
    {
        $ketThuc = $ketThuc ?? date('Y-m-d H:i:s');
        $sql = "UPDATE {$this->table} SET diem = ?, ket_thuc = ? WHERE id = ?";
        return $this->db->execute($sql, [$diem, $ketThuc, $id]);
    }
    
    /**
     * Lấy bài thi theo ID
     */
    public function getById(int $id): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    /**
     * Lấy điểm của học sinh và id lớp học
     */
    public function getPointByStudentAndClass(int $lhId, int $hsId):mixed 
    {
        $sql = "SELECT hst.diem
                FROM {$this->table} hst
                JOIN DeThi dt ON hst.de_thi_id = dt.id
                WHERE hst.hs_id = ? AND dt.lh_id = ?";
        return $this->db->fetchAll($sql, [$hsId, $lhId]);
    }

    /**
     * Lấy điểm trung bình các bài thi của học sinh theo lớp học
     */
    public function getAveragePointByStudentAndClass(int $lhId, int $hsId): mixed
    {
        $sql = "SELECT AVG(hst.diem) AS diem_tb_hs
                FROM {$this->table} hst
                JOIN DeThi dt ON hst.de_thi_id = dt.id
                WHERE hst.hs_id = ? AND dt.lh_id = ?";
        return $this->db->fetch($sql, [$hsId, $lhId]);
    }

    public function getIdDeThiById($id): int {
        $sql = "SELECT de_thi_id 
                FROM {$this->table}
                WHERE id = ?";
        return (int)$this->db->fetch($sql, [$id]) ?? 0;
    }
    /**
     * đếm số học sinh đã tham gia bài
     */
    public function countHsJoinExam($deThiId) : int {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE de_thi_id = ?";
        $result = $this->db->fetch($sql, [$deThiId]);
        return $result['count'] ?? 0;
    }
}
