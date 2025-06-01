<h1 question-title>Danh sách câu hỏi</h1>
<!-- lặp từng câu hỏi -->
<?php foreach($data['cau_hoi_list'] as $cauhoi):?>
    <div class="question-container">
        <div class="question" data-question-id="<?=$cauhoi['id']?>" onclick="toggleAnswers(this)"><?=$cauhoi['noi_dung'];?></div>
        <div class="answers">
            
            <!-- lặp từng đáp án lưu trong câu hỏi theo key là id của đáp án -->
            <ul>

            </ul>
            <?php
            $dapAn = $data['dap_an_list'][$cauhoi['id']];
            foreach($dapAn as $dapAnItem):
            ?>
                <li class="answer" 
                    data-answer-id="<?=$dapAnItem['id']?>" 
                    data-correct="<?=$dapAnItem['da_dung'] ? 'true' : 'false'?>">
                    <?=$dapAnItem['noi_dung']?>
                </li>
            <?php endforeach;?>
        </div>
    </div>
<?php endforeach;?>
<!-- Nút bấm để hiển thị form tải file -->
<button class="upload-btn" onclick="toggleUploadForm()">Tải lên file</button>
<div class="backdrop" id="backdrop"></div>
<div id="uploadForm" class="uploadFile_form">
    <form method="post" enctype="multipart/form-data">
        <div class="instructions">
            <h3>Hướng dẫn định dạng file CSV</h3>
            <p>File CSV cần có các cột sau (theo đúng thứ tự):</p>
            <p><code>nội dung,đáp án a,đáp án b,đáp án c,đáp án d,đáp án đúng,độ khó</code></p>
            <p>- <strong>nội dung</strong>: Câu hỏi (văn bản).</p>
            <p>- <strong>đáp án a, b, c, d</strong>: Các lựa chọn đáp án (văn bản).</p>
            <p>- <strong>đáp án đúng</strong>: Ký tự in thường (<code>a</code>, <code>b</code>, <code>c</code>, hoặc <code>d</code>).</p>
            <p>- <strong>độ khó</strong>: Số nguyên từ 1 đến 3 (1: dễ, 3: khó).</p>
            <p><strong>Ví dụ dòng CSV:</strong></p>
            <p><code>"Tìm nghiệm của x² - 4x + 3 = 0","x = 1, x = 3","x = -1, x = -3","x = 2, x = 4","x = -1, x = 4","a",2</code></p>
            <p>Lưu ý: Sử dụng dấu phẩy (,) để phân tách cột, và các giá trị chứa dấu phẩy phải được bao trong dấu nháy kép ("").</p>
        </div>
        <label for="fileUpload">Chọn file CSV hoặc Excel:</label>
        <input type="file" id="fileUpload" name="fileUpload" accept=".csv, .xlsx, .xls" required>
        <button type="submit">Tải lên</button>
        <button type="button" onclick="toggleUploadForm()">Hủy</button>
    </form>
</div>

<script>
    function toggleUploadForm() {
        const form = document.getElementById('uploadForm');
        const backdrop = document.getElementById('backdrop');
        const isVisible = form.classList.toggle('visible');
        backdrop.style.display = isVisible ? 'block' : 'none';
    }
    // Tự động thêm số thứ tự cho các câu hỏi
    document.querySelectorAll('.question').forEach((question, index) => {
        question.textContent = `${index + 1}\). ${question.textContent}`;
    });

    function toggleAnswers(element) {
        const answers = element.nextElementSibling;
        answers.classList.toggle('show');
    }

    const form = document.querySelector('#uploadForm form');
    const resultDiv = document.querySelector('#result');

    document.addEventListener('DOMContentLoaded', () => {
        
        console.log(form);
        form.addEventListener('submit', (e) => {
            console.log(form);
            console.log('dad');
            e.preventDefault(); // Ngăn form submit mặc định
            // Lấy file từ input
            const formData = new FormData(form);

            fetch('question/load-question', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok)
                    throw new Error(`Lỗi HTTP: ${response.status}`);

                return response.json();
            })
            .then(result => {
                if (result.status === 'success')
                    console.log(result);
                alert(result.message);
                window.location.reload();
            })
            .catch(error => {
                alert("Lỗi: " + error.message);
            });
        });
    });
</script>