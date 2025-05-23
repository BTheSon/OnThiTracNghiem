<div class="content">
    <div class="section-title">Lớp học:</div>
    <div class="class-cards">
        <?php foreach($data['info_classes'] as $classes): ?>
            <a href="teacher/class-management/<?=$classes['id']?>" class="card">
                <div class="card-mota"><?=$classes['mo_ta']?></div>
                <div class="card-header"><?=$classes['ten_lop']?></div>
            </a>
        <?php endforeach;?>
    </div>
</div>