<table class='table-exam-list' border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>STT</th>
            <th>Tên bài kiểm tra</th>
            <th>Mô tả</th>
            <th>Lớp</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($data['exams'])): ?>
            <?php foreach ($data['exams'] as $index => $exam): ?>
                <tr>
                    <td><?=$index + 1 ?></td>
                    <td><?=htmlspecialchars($exam['tieu_de']); ?></td>
                    <td><?=htmlspecialchars($exam['mo_ta']); ?></td>
                    <td><?=htmlspecialchars($exam['ten_lop']); ?></td>
                    <td><?=htmlspecialchars($exam['ngay_tao']); ?></td>
                    <td>
                        <u class='delete-exam' data-url="exam/delete/<?=$exam['id']?>" style="cursor: pointer;">Xóa</u>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Không có bài kiểm tra nào.</td>
            </tr>
        <?php endif;?>
    </tbody>
</table>
