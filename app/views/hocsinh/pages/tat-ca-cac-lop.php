<div class="content">
    <div class="section-title">Lớp học:</div>
    <div class="class-cards">
        <?php foreach($data['info_classes'] as $classes): ?>
            <a href="student/class-test" class="card">
                <div class="card-header"><?=$classes['ten_lop']?></div>
            </a>
        <?php endforeach;?>
    </div>

    <div class="button-add">
        <button class="add-button" onclick="showForm()">Tham gia lớp học</button>
    </div>
    <div class="overlay" id="formOverlay" style="display: none;">
        <div class="form-container">
            <span class="close-button" onclick="hideForm()">×</span>
            <h2>Tham gia lớp học</h2>
            <input type="text" id="classCode" placeholder="Nhập mã lớp">
            <button onclick="joinClass()">Tham gia</button>
            <div class="error-message" id="errorMessage"></div>
        </div>
    </div>
</div>
