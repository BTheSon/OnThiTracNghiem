<form method="POST" class="form-change-name">
    <label for="new_name" class="form-label">Nhập tên mới:</label>
    <input type="text" id="new_name" name="new_name" required class="form-input">
    <button type="submit" class="form-button">Đổi tên</button>
    <div class ='message'><p>
        <?=$data['message']?>
    </p></div>
</form>
