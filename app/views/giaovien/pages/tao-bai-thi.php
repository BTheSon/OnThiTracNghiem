  <style>
    
    /* Container chính */
    .form-container {
      max-width: 800px; /* Giới hạn chiều rộng */
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .form-container h2 {
      font-size: 24px;
      color: #1f2937;
      margin-bottom: 20px;
      text-align: center; /* Căn giữa tiêu đề */
    }

    /* Thông tin bài thi */
    .exam-info {
      margin-bottom: 20px;
    }

    .exam-info label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
      color: #1f2937;
    }

    .exam-info input,
    .exam-info textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #d1d5db;
      border-radius: 5px;
      font-size: 16px;
    }

    .exam-info input:focus,
    .exam-info textarea:focus {
      outline: none;
      border-color: #2563eb;
    }

    .exam-info textarea {
      resize: vertical;
      min-height: 100px;
    }

    .exam-info .form-group {
      margin-bottom: 15px;
    }

    /* Thanh search */
    .search-container {
      position: relative;
      margin-bottom: 15px;
    }

    .search-container i {
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
      color: #6b7280;
    }

    .search-container input {
      width: 100%;
      padding: 10px 10px 10px 35px;
      border: 1px solid #d1d5db;
      border-radius: 5px;
      font-size: 16px;
    }

    .search-container input:focus {
      outline: none;
      border-color: #2563eb;
    }

    /* Lọc và random */
    .filter-random-container {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-bottom: 20px;
      justify-content: center; /* Căn giữa */
      align-items: center;
    }

    .filter-container select,
    .random-form input,
    .random-form select {
      padding: 8px;
      border: 1px solid #d1d5db;
      border-radius: 5px;
      font-size: 16px;
    }

    .filter-container select:focus,
    .random-form input:focus,
    .random-form select:focus {
      outline: none;
      border-color: #2563eb;
    }

    .random-form {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .random-form input {
      width: 100px;
    }

    .random-form .selected-count {
      font-size: 14px;
      color: #1f2937;
      font-weight: bold;
    }

    .btn {
      padding: 8px 16px;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      background-color: #2563eb;
      color: white;
      transition: background-color 0.3s ease;
    }

    .btn:hover {
      background-color: #1e3a8a;
    }

    .btn-clear {
      background-color: #6b7280;
      margin-left: 10px;
    }

    .btn-clear:hover {
      background-color: #4b5563;
    }

    /* Bảng câu hỏi */
    .table-container {
      max-height: 400px; /* Kích thước cố định */
      overflow-y: auto; /* Cuộn dọc */
      overflow-x: auto; /* Cuộn ngang nếu cần */
      border: 1px solid #d1d5db;
      border-radius: 5px;
    }

    .question-table {
      width: 100%;
      border-collapse: collapse;
    }

    .question-table th,
    .question-table td {
      padding: 10px;
      border: 1px solid #d1d5db;
      text-align: left;
    }

    .question-table th {
      background-color: #e5e7eb;
      font-weight: bold;
      position: sticky;
      top: 0;
      z-index: 1;
    }

    .question-table tr:nth-child(even) {
      background-color: #f9fafb;
    }

    .question-table tr:hover {
      background-color: #e5e7eb;
    }

    .question-table input[type="checkbox"] {
      width: 18px;
      height: 18px;
    }

    /* Badge độ khó */
    .difficulty-badge {
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: bold;
      text-transform: uppercase;
    }

    .difficulty-easy {
      background-color: #dcfce7;
      color: #166534;
    }

    .difficulty-medium {
      background-color: #fef3c7;
      color: #92400e;
    }

    .difficulty-hard {
      background-color: #fecaca;
      color: #991b1b;
    }

    /* Thông tin lọc */
    .filter-info {
      margin-bottom: 10px;
      font-size: 14px;
      color: #6b7280;
      text-align: center;
    }

    .no-results {
      text-align: center;
      padding: 20px;
      color: #6b7280;
      font-style: italic;
    }

    /* Nút submit */
    .submit-btn {
      padding: 10px 20px;
      font-size: 16px;
      display: block;
      margin: 20px auto 0; /* Căn giữa nút */
    }

    /* Responsive */
    @media (max-width: 768px) {
      #main-content {
        margin-left: 0;
        margin-top: 80px; /* Điều chỉnh cho header và toggle menu */
      }

      .form-container {
        padding: 15px;
      }

      .filter-random-container {
        flex-direction: column;
        gap: 10px;
      }

      .random-form {
        flex-direction: column;
        align-items: stretch;
      }

      .random-form input,
      .random-form select {
        width: 100%;
      }

      .exam-info input,
      .exam-info textarea {
        font-size: 14px;
      }

      .table-container {
        max-height: 300px; /* Giảm chiều cao trên mobile */
      }
    }
  </style>

  <div class="form-container">
    <h2>Tạo đề thi</h2>
    <form method="POST" action="exam/create" id="create-exam-form">
      <!-- Thông tin bài thi -->
      <div class="exam-info">
        <div class="form-group">
          <label for="exam-title">Tiêu đề bài thi</label>
          <input type="text" id="exam-title" name="exam_title" placeholder="Nhập tiêu đề bài thi" required>
        </div>
        <div class="form-group">
          <label for="exam-description">Mô tả bài thi</label>
          <textarea id="exam-description" name="exam_description" placeholder="Nhập mô tả bài thi"></textarea>
        </div>
        <div class="form-group">
          <label for="exam-start-time">Thời gian làm bài</label>
          <input type="datetime-local" id="exam-start-time" name="exam_start_time" required>
        </div>
        <div class="form-group">
          <label for="exam-duration">Số phút thi</label>
          <input type="number" id="exam-duration" name="exam_duration" min="1" placeholder="Nhập số phút" required>
        </div>
      </div>

      <!-- Thanh search -->
      <div class="search-container">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Tìm kiếm câu hỏi..." name="search_query" id="search-query">
      </div>

      <!-- Lọc và random -->
      <div class="filter-random-container">
        <!-- Dropdown lọc độ khó -->
        <div class="filter-container">
          <select name="filter_difficulty" id="filter-difficulty">
            <option value="all">Lọc theo độ khó: Tất cả</option>
            <option value="dễ">Dễ</option>
            <option value="trung bình">Trung bình</option>
            <option value="khó">Khó</option>
          </select>
        </div>

        <!-- Nút xóa bộ lọc -->
        <button type="button" class="btn btn-clear" id="clear-filters">Xóa bộ lọc</button>

        <!-- Form random -->
        <div class="random-form">
          <input type="number" placeholder="Số lượng" name="random_count" min="1" max="100">
          <select name="random_difficulty">
            <option value="all">Tất cả</option>
            <option value="dễ">Dễ</option>
            <option value="trung bình">Trung bình</option>
            <option value="khó">Khó</option>
          </select>
          <button type="button" class="btn" id="random-btn">Chọn ngẫu nhiên</button>
          <span class="selected-count">Đã chọn: 0 câu hỏi</span>
        </div>
      </div>

      <!-- Thông tin lọc -->
      <div class="filter-info" id="filter-info">
        Hiển thị tất cả câu hỏi
      </div>

      <!-- Bảng danh sách câu hỏi -->
      <div class="table-container">
        <table class="question-table">
          <thead>
            <tr>
              <th style="width: 50px;"><input type="checkbox" id="select-all"></th>
              <th style="width: 60px;">STT</th>
              <th>Nội dung câu hỏi</th>
              <th style="width: 120px;">Độ khó</th>
            </tr>
          </thead>
          <tbody id="question-table-body">
            <?php foreach ($data['questions'] as $index => $question): ?>
              <?php
                    $difficultyText = '';
                    $difficultyClass = '';
                    switch ($question['do_kho']) {
                      case 1:
                        $difficultyText = 'Dễ';
                        $difficultyClass = 'difficulty-easy';
                        break;
                      case 2:
                        $difficultyText = 'Trung bình';
                        $difficultyClass = 'difficulty-medium';
                        break;
                      case 3:
                        $difficultyText = 'Khó';
                        $difficultyClass = 'difficulty-hard';
                        break;
                      default:
                        $difficultyText = 'N/A';
                    }
                  ?>
              <tr data-difficulty="<?= $difficultyText  ?>">
                <td><input type="checkbox" name="question_ids[]" value="<?= $question['id'] ?>"></td>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($question['noi_dung']) ?></td>
                <td>
                  
                  <span class="difficulty-badge <?= $difficultyClass ?>">
                    <?= htmlspecialchars($difficultyText) ?>
                  </span>
                </td>
            <?php endforeach; ?>
            <!-- Dữ liệu mẫu với nhiều câu hỏi hơn -->
            <!-- <tr data-difficulty="dễ">
              <td><input type="checkbox" name="question_ids[]" value="question_1"></td>
              <td>1</td>
              <td>1 + 1 = ?</td>
              <td><span class="difficulty-badge difficulty-easy">Dễ</span></td>
            </tr> -->
            
          </tbody>
        </table>
        <div class="no-results" id="no-results" style="display: none;">
          Không tìm thấy câu hỏi nào phù hợp với bộ lọc hiện tại.
        </div>
      </div>

      <!-- Nút submit -->
      <button type="submit" class="btn submit-btn">Tạo đề thi</button>
    </form>
  </div>
