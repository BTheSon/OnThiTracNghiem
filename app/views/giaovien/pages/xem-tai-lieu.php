<?php foreach ($data['documents'] as $document): ?>
<div class="document-item">
    <h3><?php echo htmlspecialchars($document['tieu_de']); ?></h3>
    <p><?php echo htmlspecialchars($document['mo_ta']); ?></p>
    <p><?php echo htmlspecialchars($document['ngay_dang']); ?></p>
    <button class="delete-btn" onclick="if(confirm('Bạn có chắc muốn xóa tài liệu này?')) { window.location.href = 'document/delete/<?=$document['id']?>';  }">Xóa tài liệu</button>
</div>
<?php endforeach; ?>
<?php if (empty($data['documents'])): ?>
    <p>Không có tài liệu.</p>
<?php endif; ?>