<form method="POST" class="form-change-name">
    <label for="new_name" class="form-label">Nhập tên mới:</label>
    <input type="text" id="new_name" name="new_name" required class="form-input">
    <button type="submit" class="form-button">Đổi tên</button>
    <div class ='message'><p>
        <?=$data['message']?>
    </p></div>
</form>
<?php
    $url = $_SESSION['user_role'] === 'gv'? 'teacher' : 'student'
?>
<a href="<?=$url?>" style="position: absolute; top: 10px; left: 10px; text-decoration: none; padding: 8px 16px; background: #005f99; color: #fff; border-radius: 4px;">quay lại</a>