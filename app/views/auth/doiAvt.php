
<div class="container">
    
    <h2 class="title">Đổi ảnh đại diện</h2>

    <form id="profilePictureForm">
        <div class="form-group">
            <img id="profilePreview" src="https://placehold.co/150x150/a0c4ff/001f3f?text=Ảnh+đại+diện" alt="Ảnh đại diện hiện tại" class="profile-preview">
            <label for="profilePictureInput" class="upload-button">
                <i class="fas fa-upload"></i> Chọn ảnh mới
            </label>
            <input type="file" id="profilePictureInput" accept="image/*" class="file-input" aria-label="Chọn ảnh đại diện mới">
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-button">
                <i class="fas fa-save"></i> Lưu thay đổi
            </button>
        </div>
    </form>
</div>

<script>
    const profilePictureInput = document.getElementById('profilePictureInput');
    const profilePreview = document.getElementById('profilePreview');
    const profilePictureForm = document.getElementById('profilePictureForm');

    // Xử lý khi người dùng chọn ảnh
    profilePictureInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profilePreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Xử lý khi biểu mẫu được gửi
    profilePictureForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Ngăn chặn gửi biểu mẫu mặc định

        const file = profilePictureInput.files[0];
        if (file) {
            // Ở đây bạn sẽ thêm logic để tải ảnh lên máy chủ.
            // Ví dụ: sử dụng FormData và Fetch API để gửi file.
            const formData = new FormData();
            formData.append('profilePicture', file);
            fetch('user/change-avatar', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success)
                    alert(data.message);
                else
                    alert(data.message);
            })
            .catch(error => {
                console.error('Lỗi khi tải ảnh lên:', error);
                showMessageBox('Có lỗi xảy ra khi cập nhật ảnh đại diện.');
            });

            // Trong môi trường thực tế, bạn sẽ gửi file lên server ở đây.
        } else {
            alert('Vui lòng chọn một ảnh để cập nhật.');
        }
    });
</script>
