// Biến lưu trữ trạng thái
    let allQuestions = [];
    let filteredQuestions = [];

    // Khởi tạo dữ liệu
    function initializeQuestions() {
      const rows = document.querySelectorAll('#question-table-body tr');
      allQuestions = Array.from(rows).map(row => ({
        element: row,
        content: row.cells[2].textContent.toLowerCase(),
        difficulty: row.getAttribute('data-difficulty').toLowerCase(),
        id: row.querySelector('input[type="checkbox"]').value
      }));
      filteredQuestions = [...allQuestions];
      updateFilterInfo();
    }

    // Chọn/bỏ chọn tất cả checkbox
    document.getElementById('select-all').addEventListener('change', function() {
      const visibleCheckboxes = document.querySelectorAll('#question-table-body tr:not([style*="display: none"]) input[name="question_ids[]"]');
      visibleCheckboxes.forEach(checkbox => checkbox.checked = this.checked);
      updateSelectedCount();
    });

    // Cập nhật trạng thái "select all" khi checkbox thay đổi
    function updateSelectAllState() {
      const visibleCheckboxes = document.querySelectorAll('#question-table-body tr:not([style*="display: none"]) input[name="question_ids[]"]');
      const checkedBoxes = document.querySelectorAll('#question-table-body tr:not([style*="display: none"]) input[name="question_ids[]"]:checked');
      const selectAllCheckbox = document.getElementById('select-all');
      
      if (visibleCheckboxes.length === 0) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
      } else if (checkedBoxes.length === visibleCheckboxes.length) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = true;
      } else if (checkedBoxes.length > 0) {
        selectAllCheckbox.indeterminate = true;
        selectAllCheckbox.checked = false;
      } else {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
      }
    }

    // Lắng nghe sự thay đổi của các checkbox câu hỏi
    document.addEventListener('change', function(e) {
      if (e.target.name === 'question_ids[]') {
        updateSelectedCount();
        updateSelectAllState();
      }
    });

    // Tìm kiếm câu hỏi
    document.getElementById('search-query').addEventListener('input', function() {
      updateTable();
    });

    // Lọc theo độ khó
    document.getElementById('filter-difficulty').addEventListener('change', function() {
      updateTable();
    });

    // Xóa bộ lọc
    document.getElementById('clear-filters').addEventListener('click', function() {
      document.getElementById('search-query').value = '';
      document.getElementById('filter-difficulty').value = 'all';
      updateTable();
    });

    // Hàm cập nhật bảng
    function updateTable() {
      const query = document.getElementById('search-query').value.toLowerCase().trim();
      const difficulty = document.getElementById('filter-difficulty').value.toLowerCase();
      const rows = document.querySelectorAll('#question-table-body tr');
      const noResults = document.getElementById('no-results');
      let visibleCount = 0;

      rows.forEach(row => {
        const content = row.cells[2].textContent.toLowerCase();
        const rowDifficulty = row.getAttribute('data-difficulty').toLowerCase();
        
        const matchesQuery = !query || content.includes(query);
        const matchesDifficulty = difficulty === 'all' || rowDifficulty === difficulty;
        
        if (matchesQuery && matchesDifficulty) {
          row.style.display = '';
          visibleCount++;
        } else {
          row.style.display = 'none';
        }
      });

      // Hiển thị thông báo không có kết quả
      if (visibleCount === 0) {
        noResults.style.display = 'block';
      } else {
        noResults.style.display = 'none';
      }

      // Cập nhật thông tin lọc
      updateFilterInfo(query, difficulty, visibleCount);
      
      // Cập nhật trạng thái select all
      updateSelectAllState();
    }

    // Cập nhật thông tin lọc
    function updateFilterInfo(query = '', difficulty = 'all', visibleCount = null) {
      const filterInfo = document.getElementById('filter-info');
      const totalQuestions = allQuestions.length;
      
      if (visibleCount === null) {
        visibleCount = document.querySelectorAll('#question-table-body tr:not([style*="display: none"])').length;
      }

      let infoText = '';
      
      if (query && difficulty !== 'all') {
        infoText = `Hiển thị ${visibleCount}/${totalQuestions} câu hỏi với từ khóa "${query}" và độ khó "${difficulty}"`;
      } else if (query) {
        infoText = `Hiển thị ${visibleCount}/${totalQuestions} câu hỏi với từ khóa "${query}"`;
      } else if (difficulty !== 'all') {
        infoText = `Hiển thị ${visibleCount}/${totalQuestions} câu hỏi với độ khó "${difficulty}"`;
      } else {
        infoText = `Hiển thị tất cả ${totalQuestions} câu hỏi`;
      }
      
      filterInfo.textContent = infoText;
    }

    // Xử lý nút random
    document.getElementById('random-btn').addEventListener('click', function() {
      const count = parseInt(document.querySelector('input[name="random_count"]').value) || 0;
      const difficulty = document.querySelector('select[name="random_difficulty"]').value;
      
      if (count <= 0) {
        alert('Vui lòng nhập số lượng câu hỏi cần chọn ngẫu nhiên');
        return;
      }
      
      // Lấy danh sách câu hỏi hiện đang hiển thị
      const visibleRows = Array.from(document.querySelectorAll('#question-table-body tr:not([style*="display: none"])'));
      let availableRows = visibleRows;
      
      // Lọc theo độ khó nếu có
      if (difficulty !== 'all') {
        availableRows = visibleRows.filter(row => 
          row.getAttribute('data-difficulty').toLowerCase() === difficulty.toLowerCase()
        );
      }
      
      if (availableRows.length === 0) {
        alert('Không có câu hỏi nào phù hợp để chọn ngẫu nhiên');
        return;
      }
      
      // Bỏ chọn tất cả checkbox
      document.querySelectorAll('input[name="question_ids[]"]').forEach(checkbox => {
        checkbox.checked = false;
      });
      
      // Chọn ngẫu nhiên
      const shuffled = availableRows.sort(() => 0.5 - Math.random());
      const selected = shuffled.slice(0, Math.min(count, shuffled.length));
      
      selected.forEach(row => {
        row.querySelector('input[type="checkbox"]').checked = true;
      });

      // Cập nhật số câu hỏi được chọn
      updateSelectedCount();
      updateSelectAllState();
      
      // Thông báo
      const difficultyText = difficulty === 'all' ? 'tất cả độ khó' : `độ khó "${difficulty}"`;
      alert(`Đã chọn ngẫu nhiên ${selected.length} câu hỏi với ${difficultyText}`);
    });

    // Cập nhật số câu hỏi được chọn
    function updateSelectedCount() {
      const selectedCount = document.querySelectorAll('input[name="question_ids[]"]:checked').length;
      document.querySelector('.selected-count').textContent = `Đã chọn: ${selectedCount} câu hỏi`;
    }

    // Validate form trước khi submit
    document.getElementById('create-exam-form').addEventListener('submit', function(e) {
      const title = document.getElementById('exam-title').value.trim();
      const duration = document.getElementById('exam-duration').value;
      const startTime = document.getElementById('exam-start-time').value;
      const selectedQuestions = document.querySelectorAll('input[name="question_ids[]"]:checked').length;

      if (!title) {
        alert('Vui lòng nhập tiêu đề bài thi');
        e.preventDefault();
        return;
      }
      if (!startTime) {
        alert('Vui lòng chọn thời gian làm bài');
        e.preventDefault();
        return;
      }
      if (!duration || duration < 1) {
        alert('Vui lòng nhập số phút thi hợp lệ');
        e.preventDefault();
        return;
      }
      if (selectedQuestions === 0) {
        alert('Vui lòng chọn ít nhất một câu hỏi');
        e.preventDefault();
        return;
      }
      
      // Xác nhận tạo đề thi
      if (!confirm(`Bạn có chắc chắn muốn tạo đề thi với ${selectedQuestions} câu hỏi?`)) {
        e.preventDefault();
        return;
      }
    });

    // Khởi tạo khi trang load
    document.addEventListener('DOMContentLoaded', function() {
      initializeQuestions();
      updateSelectedCount();
    });