<?php foreach ($data['documents'] as $document): ?>
<div class="document-item">
    <h3><?php echo htmlspecialchars($document['tieu_de']); ?></h3>
    <p><?php echo htmlspecialchars($document['mo_ta']); ?></p>
</div>
<?php endforeach; ?>