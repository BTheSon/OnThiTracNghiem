<div class="content">
    <div class="section-title">Lớp học:</div>
    <div class="class-cards">
        <?php foreach($data['info_classes'] as $classes): ?>
            <a href="teacher/class-management/<?=$classes['id']?>" class="card">
                <div class="card-mota"><?=$classes['mo_ta']?></div>
                <div class="card-header"><?=$classes['ten_lop']?></div>
                <button type="button" class="delete-class-btn" onclick="confirmDelete(event, <?=$classes['id']?>)">
                    <i class="fa-solid fa-trash"></i>
                </button>
                <script>
                function confirmDelete(event, classId) {
                    event.preventDefault();
                    if (confirm('Bạn có chắc chắn muốn rời lớp này?')) {
                        window.location.href = 'teacher/delete-class/' + classId;
                    }
                }
                </script>
            </a>
        <?php endforeach;?>
    </div>
</div>  