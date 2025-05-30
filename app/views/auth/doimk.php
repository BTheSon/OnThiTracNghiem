<form method="POST" class="form-change-password">
    <label for="old_password">Mật khẩu cũ:</label>
    <input type="password" id="old_password" name="old_password" required class="form-control"><br>

    <label for="new_password">Mật khẩu mới:</label>
    <input type="password" id="new_password" name="new_password" required class="form-control"><br>

    <label for="confirm_password">Xác nhận mật khẩu mới:</label>
    <input type="password" id="confirm_password" name="confirm_password" required class="form-control"><br>
    <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>

        <div class ='message'><p>
        <?=$data['message']?>
    </p></div>
</form>