<!-- 
    $data = [
        info_students => [
            0 => [
                'hs_id' => 1,
                'lh_id' => 1,
                'ho_ten' => 'Nguyen Van A',
                'email' => 'nguyenvana@example.com',
                'anh' => 'path/to/image.jpg',
                'diem_tb_hs' => 8.5
            ],
            1 => [
                'hs_id' => 2,
                'lh_id' => 1,
                'ho_ten' => 'Nguyen Van B',
                'email' => 'nguyenvanb@example.com',
                'anh' => 'path/to/image.jpg',
                'diem_tb_hs' => 7.5
            ]
        ],
        info_classes => [
            'id' => 1,
            'ma_lop' => 'Lop 1',
            'ten_lop' => 'Lop 1',
            'mo_ta' => 'Mo ta lop 1'
        ]
    ]
-->

<div class="container-option">
    <div class="ma-lop"> Mã Lớp <br> <?=$data['info_classes']['ma_lop'] ?></div>
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
                <?php echo'<script>console.log('.json_encode($data).')</script>';?>
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
                <a onclick="loadForm('TestView/test')">
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

<div id="form-container" style="  position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;">
    <!-- <form id="uploadForm" method="post">
        <label for="tai_lieu_file">Chọn file tài liệu:</label>
        <input type="file" name="tai_lieu_file" id="tai_lieu_file" required>
        <input type="text" name="tieu_de" id="tieu_de" placeholder="Tiêu đề" required>
        <input type="text" name="mo_ta" id="mo_ta" placeholder="Mô tả">
        <button type="submit">Tải lên</button>
    </form> -->
</div>