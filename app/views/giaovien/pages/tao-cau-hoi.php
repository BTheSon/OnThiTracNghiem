<!-- Giao diện form -->
<form method="post">
<div>
    <label for="lop">Lớp học:</label>
    <select name="lop" id="lop" required>
    </select>
</div>
<div>
    <label for="do_kho">Độ khó:</label>
    <select name="do_kho" id="do_kho" required>
        <option value="">--Chọn độ khó--</option>
        <option value="1">mức 1</option>
        <option value="2">mức 2</option>
        <option value="3">mức 3</option>
        <option value="4">mức 4</option>
    </select>
</div>
<div>
    <label for="noi_dung">Nội dung câu hỏi:</label>
    <textarea name="noi_dung" id="noi_dung" rows="4" required></textarea>
</div>
<div>
    <label for="dap_an_1">Đáp án 1:</label>
    <input type="text" name="dap_an_1" id="dap_an_1" required>
</div>
<div>
    <label for="dap_an_2">Đáp án 2:</label>
    <input type="text" name="dap_an_2" id="dap_an_2" required>
</div>
<div>
    <label for="dap_an_3">Đáp án 3:</label>
    <input type="text" name="dap_an_3" id="dap_an_3" required>
</div>
<div>
    <label for="dap_an_4">Đáp án đúng:</label>
    <input type="text" name="dap_an_4" id="dap_an_4" required>
</div>
<div>
    <button type="submit">Thêm câu hỏi</button>
</div>
</form>