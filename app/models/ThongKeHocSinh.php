<?php
namespace App\Models;
use App\Core\Model;

// lấy các loại thống kê của học sinh
class ThongKeHocSinh extends Model {
    public function __construct() {
        parent::__construct();
        $this->table = "NguoiDung";
    }

    public function getStudentAllStats() : ?array {
        $sql = "SELECT 
            nd.id as student_id,
            nd.ho_ten,
            nd.email,
            lh.ten_lop,
            lh.ma_lop,
            MIN(hsl.ngay_tham_gia) as ngay_tham_gia,
            hst.diem,
            hst.bat_dau,
            hst.ket_thuc,
            dt.id as exam_id
        FROM NguoiDung nd
        LEFT JOIN HocSinhLop hsl ON nd.id = hsl.hs_id
        LEFT JOIN LopHoc lh ON hsl.lh_id = lh.id
        LEFT JOIN HocSinhThi hst ON nd.id = hst.hs_id
        LEFT JOIN DeThi dt ON hst.de_thi_id = dt.id
        WHERE nd.vai_tro = 'hs'
        GROUP BY nd.id, nd.ho_ten, nd.email, lh.id, lh.ten_lop, lh.ma_lop, hst.id, hst.diem, hst.bat_dau, hst.ket_thuc, dt.id
        ORDER BY nd.ho_ten";

        return $this->db->fetchAll($sql) ?? null;
    }
}