<style>
    
</style>
<div class="container">
    <!-- Thông tin học sinh -->
    <div class="student-info">
        <h3>Thông tin học sinh</h3>
        <p><strong>Họ và tên:</strong> <?php echo htmlspecialchars($data['student_info']['ho_ten'] ?? 'Không xác định'); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($data['student_info']['email'] ?? 'Không xác định'); ?></p>
        <p class="average-score"><strong>Điểm trung bình:</strong> <?php echo !empty($data['averageScore']) ? number_format($data['averageScore'], 1) : 'Chưa có'; ?></p>
    </div>

    <!-- Danh sách bài thi -->
    <h3>Danh sách bài thi</h3>
    <table class="exam-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên đề thi</th>
                <th>Ngày thi</th>
                <th>Thời gian (phút)</th>
                <th>Trạng thái</th>
                <th>Điểm</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data['student_exam'])): ?>
                <?php foreach ($data['student_exam'] as $index => $exam): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($exam['tieu_de']); ?></td>
                        <td><?php echo $exam['bat_dau'] ? date('Y-m-d H:i', strtotime($exam['bat_dau'])) : 'Chưa xác định'; ?></td>
                        <td><?php echo htmlspecialchars($exam['tg_phut']); ?></td>
                        <td class="<?php echo $exam['diem'] !== null ? 'status-done' : 'status-pending'; ?>">
                            <?php echo $exam['trang_thai']; ?>
                        </td>
                        <td><?php echo $exam['diem'] !== null ? number_format((float)$exam['diem'], 1) . '/' . '10.0' : '-'; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="no-data">Chưa có bài thi nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Nút quay lại -->
    <a href="teacher/class-management/<?php echo htmlspecialchars($_SESSION['class_id'] ?? ''); ?>" class="btn-back">Quay lại danh sách lớp</a>
</div>