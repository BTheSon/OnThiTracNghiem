<div class="content">
    <div class="section-title">Lớp học:</div>
    <div class="class-cards">
    <?php foreach($data['info_classes'] as $classes): ?>
        <a href="teacher/class-management/<?=$classes['id']?>" class="card" id="card-<?=$classes['id']?>" onclick="cardClicked(event)">
            <div class="card-mota" id="mota-<?=$classes['id']?>"><?=$classes['mo_ta']?></div>
            <div class="card-header" id="header-<?=$classes['id']?>"><?=$classes['ten_lop']?></div>

            <!-- Nút 3 chấm -->
            <button class="menu-button" onclick="toggleMenu(event, <?=$classes['id']?>)">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </button>

            <!-- Menu tùy chọn -->
            <div class="menu-options" id="menu-<?=$classes['id']?>">
                <button onclick="showEditModal(<?=$classes['id']?>); event.stopPropagation(); event.preventDefault();">Sửa</button>
                <button onclick="confirmDelete(event, <?=$classes['id']?>)">Xóa</button>
            </div>
        </a>
    <?php endforeach;?>
    <!-- Modal sửa -->
    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <h3>Chỉnh sửa lớp học</h3>
            <label for="edit-header">Tên lớp học</label>
            <input type="text" id="edit-header" placeholder="Tên lớp học">
            <label for="edit-mota">Mô tả lớp học</label>
            <input type="text" id="edit-mota" placeholder="Mô tả lớp học">
            <button onclick="saveEdit()">Lưu</button>
            <button onclick="closeEditModal()">Hủy</button>
        </div>
    </div>
</div>
