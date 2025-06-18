<div class="section-title">
    Lớp học:
</div>
<div class="class-cards">
    <?php foreach($data['info_classes'] as $classes): ?>
        <a href="student/class-preview/<?=$classes['lh_id']?>" class="card">
            <div class="card-mota"><?=$classes['mo_ta']?></div>
            <div class="card-header"><?=$classes['ten_lop']?></div>
            <div class="card-teacher"><?=$classes['ten_gv']?></div>
                <button type="button" class="delete-class-btn" onclick="confirmDelete(event, <?=$classes['lh_id']?>)" title="Thoát lớp học">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
        </a>
    <?php endforeach;?>
</div>
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
