<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\ThongKeHocSinh;
use DateTime;

/**
 * Stats Controller
 * Xử lý các yêu cầu liên quan đến thống kê học sinh.
 * Bao gồm thống kê điểm, thời gian làm bài và tỷ lệ hoàn thành.
 */
class Stats extends Controller {

    private ThongKeHocSinh $thong_ke_hoc_sinh;
    public function __construct() {
        $this->thong_ke_hoc_sinh = $this->model("ThongKeHocSinh");
    }

    /**
     * Hiển thị thống kê tất cả học sinh.
     * Chỉ dành cho giáo viên.
     * response: xuất file Excel thống kê
     */
    public function all_student(): void {
        $rawData = $this->thong_ke_hoc_sinh->getStudentAllStats();
        if (empty($rawData)){
            return;
        }

        $formatData = $this->formatStudentStatsData($rawData);

        $this->exportStudentStatsToExcel($formatData);
        
    }

    /**
     * hàm chuẩn hóa dữ liệu thô từ db
     */
    function formatStudentStatsData($rawData) {
        $formattedData = [];
        $studentGroups = [];
        
        // Nhóm dữ liệu theo student_id
        foreach ($rawData as $row) {
            $studentId = $row['student_id'];
            if (!isset($studentGroups[$studentId])) {
                $studentGroups[$studentId] = [];
            }
            $studentGroups[$studentId][] = $row;
        }
        
        // Xử lý từng học sinh
        foreach ($studentGroups as $studentId => $studentData) {
            $firstRow = $studentData[0];
            
            // Lọc các bài thi có điểm (loại bỏ null)
            $validExams = array_filter($studentData, function($row) {
                return !is_null($row['diem']);
            });
            
            // Tính toán thống kê
            $soBaiThi = count($validExams);
            $tongBaiThi = count(array_unique(array_column($studentData, 'exam_id')));
            $tongBaiThi = $tongBaiThi > 0 ? $tongBaiThi : 1; // Tránh chia cho 0
            
            $diemArray = array_column($validExams, 'diem');
            $diemTB = $soBaiThi > 0 ? array_sum($diemArray) / $soBaiThi : 0;
            $diemCaoNhat = $soBaiThi > 0 ? max($diemArray) : 0;
            $diemThapNhat = $soBaiThi > 0 ? min($diemArray) : 0;
            
            // Tính thời gian trung bình
            $thoiGianArray = [];
            foreach ($validExams as $exam) {
                if ($exam['bat_dau'] && $exam['ket_thuc']) {
                    $start = new DateTime($exam['bat_dau']);
                    $end = new DateTime($exam['ket_thuc']);
                    $diff = $end->diff($start);
                    $thoiGianArray[] = $diff->h * 60 + $diff->i;
                }
            }
            $thoiGianTB = count($thoiGianArray) > 0 ? array_sum($thoiGianArray) / count($thoiGianArray) : 0;
            
            // Tỷ lệ hoàn thành
            $tyLeHoanThanh = ($soBaiThi / $tongBaiThi) * 100;
            
            // Thêm vào mảng kết quả
            $formattedData[] = [
                'ho_ten' => $firstRow['ho_ten'] ?: 'N/A',
                'email' => $firstRow['email'] ?: 'N/A',
                'ten_lop' => $firstRow['ten_lop'] ?: 'N/A',
                'ma_lop' => $firstRow['ma_lop'] ?: 'N/A',
                'ngay_tham_gia' => $firstRow['ngay_tham_gia'] ?: date('Y-m-d'),
                'so_bai_thi' => $soBaiThi,
                'diem_tb' => round($diemTB, 1),
                'diem_cao_nhat' => round($diemCaoNhat, 1),
                'diem_thap_nhat' => round($diemThapNhat, 1),
                'ty_le_hoan_thanh' => round($tyLeHoanThanh, 0),
                'thoi_gian_tb' => round($thoiGianTB, 0)
            ];
        }
        
        return $formattedData;
    }

    /**
     * hàm tiện dụng xuất html thành excel
     */
    function exportStudentStatsToExcel($data, $filename = 'thong_ke_hoc_sinh.xls') {
        // Xóa mọi output trước đó
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Set headers để browser hiểu đây là file Excel
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');
    
        // Bắt đầu output buffer
        ob_start();

        // Thêm BOM để hỗ trợ UTF-8
        echo "\xEF\xBB\xBF";
        ?>
        <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <!--[if gte mso 9]>
            <xml>
            <x:ExcelWorkbook>
            <x:ExcelWorksheets>
            <x:ExcelWorksheet>
            <x:Name>Thống kê học sinh</x:Name>
            <x:WorksheetOptions>
            <x:DisplayGridlines/>
            </x:WorksheetOptions>
            </x:ExcelWorksheet>
            </x:ExcelWorksheets>
            </x:ExcelWorkbook>
            </xml>
            <![endif]-->
        </head>
        <body>
        <table border="1">
            <thead>
                <tr style="background-color: #4CAF50; color: white; font-weight: bold;">
                    <th>STT</th>
                    <th>Họ Tên</th>
                    <th>Email</th>
                    <th>Lớp</th>
                    <th>Mã Lớp</th>
                    <th>Số Bài Thi</th>
                    <th>Điểm TB</th>
                    <th>Điểm Cao Nhất</th>
                    <th>Điểm Thấp Nhất</th>
                    <th>Tỷ Lệ Hoàn Thành (%)</th>
                    <th>Thời Gian TB (phút)</th>
                    <th>Ngày Tham Gia</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $stt = 1;
                foreach ($data as $row): ?>
                    <tr>
                        <td><?= $stt ?></td>
                        <td><?= htmlspecialchars($row['ho_ten'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['ten_lop'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($row['ma_lop'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $row['so_bai_thi'] ?></td>
                        <td><?= number_format($row['diem_tb'], 1) ?></td>
                        <td><?= number_format($row['diem_cao_nhat'], 1) ?></td>
                        <td><?= number_format($row['diem_thap_nhat'], 1) ?></td>
                        <td><?= number_format($row['ty_le_hoan_thanh'], 0) ?></td>
                        <td><?= number_format($row['thoi_gian_tb'], 0) ?></td>
                        <td><?= date('d/m/Y', strtotime($row['ngay_tham_gia'])) ?></td>
                    </tr>
                <?php 
                $stt++;
                endforeach; ?>
            </tbody>
        </table>
        </body>
        </html>
        <?php
        
        // Gửi output và dọn dẹp buffer
        $output = ob_get_clean();
        echo $output;
        exit;
    }


}