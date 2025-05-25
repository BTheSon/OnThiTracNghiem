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
    <div class="search-student">
        <input type="text" id="search-student-input" placeholder="Tìm kiếm học sinh theo tên...">
        <button type="button" id="search-student-btn">
            <i class="fas fa-search"></i>
        </button>
    </div>
    <button class="thong-ke">
        Thống kê lớp học
        <i class="fa-solid fa-chart-line"></i>
    </button>
</div>

<p class="header-dsHs">Danh sách học sinh:</p>
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
                <a onclick="loadForm('TestView/test', 'public/js/form-add-tl.js')">
                    <i class="fas fa-file-alt"></i>
                    Tạo bài thi
                </a>
            </li>
            <li class="option">
                <a onclick="loadForm('document/form', 'public/js/form-add-tl.js')">
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
<div class="overlay" id="formOverlay" style="display: none;">
    <div id="form-container" class="form-container">
        <span class="close-button" onclick="hideForm()">
            <i class="fa-solid fa-circle-xmark"></i>
        </span>
        <!-- 
            Form load vào đây
            kích thước form trên màng hình phụ thuộc vào view được load vào bên trong
        -->
        <div id="form-content"></div> 
    </div>
</div>