<form action="xu-ly-tao-bai-thi.php" method="post">
    <div>
        <label for="ngay_gio_thi">Ngày giờ thi:</label>
        <input type="datetime-local" id="ngay_gio_thi" name="ngay_gio_thi" required>
    </div>
    <div>
        <label for="thoi_gian_lam_bai">Thời gian làm bài (phút):</label>
        <input type="number" id="thoi_gian_lam_bai" name="thoi_gian_lam_bai" min="1" required>
    </div>
  
    <div>
        <button type="submit">Tạo bài thi</button>
    </div>
</form>         