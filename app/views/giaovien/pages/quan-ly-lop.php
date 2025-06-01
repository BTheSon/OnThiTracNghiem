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
    .main-content-wrapper {
        display: flex;
        flex-wrap: wrap; /* Allows columns to wrap on smaller screens */
        justify-content: center; /* Centers the columns */
        gap: 2rem; /* Space between columns */
        padding: 2rem; /* Overall padding around the content */
        align-items: flex-start; /* Align items to the top */
    }
    #notification-container {
        flex: 1; /* Allows it to grow and shrink */
        min-width: 320px; /* Minimum width for notification column */
        max-width: 45%; /* Max width for left column */
        padding: 1.5rem;
        background-color: #f0f2f5; /* Match body background or slightly lighter */
        border-radius: 12px;
        max-height: 85vh; /* Giới hạn chiều cao tối đa của container */
        overflow-y: auto; /* Thêm thanh cuộn dọc khi nội dung vượt quá chiều cao */
        box-shadow: inset 0 0 10px rgba(0,0,0,0.05); /* Thêm bóng đổ nhẹ vào bên trong */
    }

    /* Custom Scrollbar Styles for WebKit browsers (Chrome, Safari, Edge) */
    #notification-container::-webkit-scrollbar {
        width: 8px; /* Chiều rộng của thanh cuộn */
    }

    #notification-container::-webkit-scrollbar-track {
        background: #e0e0e0; /* Màu nền của rãnh cuộn */
        border-radius: 10px;
    }

    #notification-container::-webkit-scrollbar-thumb {
        background: #888; /* Màu của "cục" cuộn */
        border-radius: 10px;
    }

    #notification-container::-webkit-scrollbar-thumb:hover {
        background: #555; /* Màu của "cục" cuộn khi hover */
    }
    /* Styles for the Statistics Container (Right Column) */
    .thong-ke-container {
        flex: 2; /* Allows it to take more space */
        min-width: 450px; /* Minimum width for stats column */
        max-width: 55%; /* Max width for right column */
        background-color: #ffffff; /* White background for the whole right column */
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        padding: 1.5rem; /* Padding inside this container */
    }

    /* Styles for the Notification Creation Form */
    .notification-form-card {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        padding: 1.5rem;
        margin-bottom: 2rem; /* Space below the form */
        border: 1px solid #e0e0e0;
    }

    .notification-form-card h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .notification-form-card label {
        display: block;
        font-size: 0.9rem;
        font-weight: 500;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .notification-form-card input[type="text"],
    .notification-form-card textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 1rem;
        color: #334155;
        margin-bottom: 1rem;
        box-sizing: border-box; /* Include padding in width */
    }

    .notification-form-card textarea {
        min-height: 100px;
        resize: vertical; /* Allow vertical resizing */
    }

    .notification-form-card button {
        background-color: #4285F4;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s ease;
        width: 100%; /* Full width button */
    }

    .notification-form-card button:hover {
        background-color: #3367D6;
    }

    /* Styles for the Google Classroom-like Item Card (Notifications) */
    .classroom-item-card {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        border: 1px solid #e0e0e0;
        margin-bottom: 1.5rem; /* Space between cards */
    }

    .item-header {
        background-color: #4285F4; /* Google Blue - can be dynamic per class */
        color: white;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(255,255,255,0.2);
    }

    .item-header h2 {
        font-size: 1.3rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .item-header .icon {
        font-size: 1.5rem;
        color: white;
    }

    .item-time {
        font-size: 0.8rem;
        font-weight: 500;
        opacity: 0.9;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .item-content {
        padding: 1.5rem;
        background-color: #ffffff;
        color: #334155;
    }

    .item-content p {
        font-size: 1rem;
        color: #475569;
        line-height: 1.6;
    }

    /* style list student */
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

<div class="main-content-wrapper">
    <div id="notification-container">
        <div class="notification-form-card">
            <h2><i class="fas fa-plus-circle mr-2 text-blue-500"></i>Tạo Thông Báo Mới</h2>
            <form id="notification-form">
                <div class="mb-4">
                    <label for="notification-title">Tiêu đề:</label>
                    <input type="text" id="notification-title" placeholder="Nhập tiêu đề thông báo" required>
                </div>
                <div class="mb-4">
                    <label for="notification-description">Mô tả:</label>
                    <textarea id="notification-description" placeholder="Nhập nội dung mô tả thông báo" required></textarea>
                </div>
                <button type="submit">Gửi Thông Báo</button>
            </form>
        </div>

        <div id="notification-list">
            <div class="classroom-item-card">
                <div class="item-header">
                    <h2 class="text-white">
                        <i class="fas fa-bullhorn icon"></i>Lịch Thi Cuối Kỳ Đã Được Cập Nhật
                    </h2>
                    <span class="item-time">
                        <i class="far fa-calendar-alt mr-1"></i>Ngày 01/06/2025
                    </span>
                </div>
                <div class="item-content">
                    <p>Các bạn học sinh lớp Lập Trình Web Nâng Cao chú ý: Lịch thi cuối kỳ đã được công bố chính thức. Vui lòng truy cập cổng thông tin sinh viên để xem chi tiết và chuẩn bị tốt nhất cho kỳ thi sắp tới.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="thong-ke-container">
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
    </div>
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