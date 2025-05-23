<form method="post">
    <label for="tai_lieu_file">Chọn file tài liệu:</label>
    <input type="file" name="tai_lieu_file" id="tai_lieu_file" required>
    <input type="text" name="tieu_de" id="tieu_de" placeholder="Tiêu đề" required>
    <input type="text" name="mo_ta" id="mo_ta" placeholder="Mô tả" >
    <button type="submit" onclick ="addTaiLieu()    " >Tải lên</button>
</form>