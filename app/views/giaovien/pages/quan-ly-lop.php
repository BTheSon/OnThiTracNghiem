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


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Khoảng điểm',    'Số học sinh'],
            ['0 - 4.5 điểm',   <?=$data['class_statistics']['kem']?>],  // nhúng data vào
            ['5.0 - 6.9 điểm', <?=$data['class_statistics']['trung_binh']?>],
            ['7.0 - 8.9 điểm', <?=$data['class_statistics']['kha']?>],
            ['9.0 - 10 điểm',  <?=$data['class_statistics']['gioi']?>]
        ]);

        var options = {
            title: 'Biểu đồ phân bố điểm học sinh',
            pieHole: 0.4,
            pieStartAngle: 45,
            colors: ['#e74c3c', '#f39c12', '#2ecc71', '#3498db'],
            legend: { position: 'bottom', textStyle: { fontSize: 14, color: '#2c3e50' }},
            titleTextStyle: { fontSize: 20, bold: true, color: '#2c3e50' },
            slices: {
                2: { offset: 0.1 },
                3: { offset: 0.05 }
            },
            tooltip: { text: 'both' },
            chartArea: { width: '85%', height: '60%' }
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
</script>

<style>

    .container {
    max-width: 900px;
    margin: auto;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    padding: 30px;
    }

    .description {
    text-align: center;
    font-size: 16px;
    margin-bottom: 30px;
    color: #555;
    }

    #piechart {
    height: 450px;
    }

    .table-box {
    margin-top: 40px;
    }

    table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    }

    th, td {
    padding: 12px 15px;
    border: 1px solid #ccc;
    text-align: center;
    }

    th {
    background-color: #3498db;
    color: #fff;
    }

    tfoot td {
    background-color: #ecf0f1;
    font-weight: bold;
    }

    .footer-note {
    margin-top: 30px;
    font-size: 14px;
    color: #777;
    font-style: italic;
    text-align: right;
    }

    .fa {
    margin-right: 6px;
    }
</style>


<div class="container-option">
    <div class="ma-lop"> Mã Lớp <br> <?=$data['info_classes']['ma_lop'] ?></div>
    <div class="search-student">
        <form action="teacher/class-management/<?= $data['info_classes']['id'] ?>" method="get" class="search-student-form">
            <input type="text" id="search-student-input" name="search" placeholder="Tìm kiếm học sinh theo tên..." class="search-input">
            <button type="submit" id="search-student-btn" class="search-btn">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
    <button class="thong-ke" onclick="window.location.href='classroom/statistics'">
        <i class="fa-solid fa-download"></i>
        Xuất file excel
    </button>
</div>

<div class="container">
    <div id="piechart"></div>
</div>

<p class="header-dsHs">Danh sách học sinh:</p>
<div class="ds-lop">
    <table border="1">
        <thead>
            <tr>
                <th>STT</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Chi tiết</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['info_students'] as $index => $student): ?>
                <?php echo'<script>console.log('.json_encode($data).')</script>';?>
                <tr onclick="window.location.href='classroom/view-student-exams/<?= $student['hs_id'] ?>'" style="cursor: pointer;">
                    <td><?= $index + 1 ?></td>
                    <td><?= $student['ho_ten'] ?></td>
                    <td><?= $student['email'] ?></td>
                    <td>
                        <i class="fa-duotone fa-solid fa-envelope-open-text" style="--fa-primary-color: #74C0FC; --fa-secondary-color: #74C0FC;"></i>
                    </td>
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
                <a onclick="loadForm('exam/form-create', 'public/js/form-create-exam.js')">
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
                <a onclick="loadForm('Question/form-create')">
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