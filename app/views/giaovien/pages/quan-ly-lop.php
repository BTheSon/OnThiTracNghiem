<div class="container-option">

    <div class="ma-lop"> Mã Lớp</div>
    <div class="tim-kiem">Tìm kiếm học sinh</div>
    <div class="thong-ke"> Thống kê lớp học</div>
</div>

<p>Danh sách học sinh:</p>
<div class="ds-lop">
    <table border="1">
        <thead>
            <tr>
                <th>STT</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Điểm trung bình các bài thi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['info_students'] as $index => $student): ?>
                <?php echo'<script>console.log('.json_encode($data['info_students']).')</script>';?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $student['ho_ten'] ?></td>
                    <td><?= $student['email'] ?></td>
                    <td><?= $student['diem_tb_hs'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="dropdown-container">
        <div class="dropdown-trigger">
            <i class="fas fa-plus"></i>
            Tạo
        </div>
        <div class="dropdown-menu">
            <ul class="options">
                <li class="option">
                    <a href="">
                        <i class="fas fa-file-alt"></i>
                        Tạo bài thi
                    </a>
                </li>
                <li class="option">
                    <a href="">
                        <i class="fas fa-folder-plus"></i>
                        Thêm tài liệu
                    </a>
                </li>
                <li class="option">
                    <a href="">
                        <i class="fas fa-question-circle"></i>
                        Thêm câu hỏi
                    </a>
                </li>
            </ul>
        </div>
    </div>