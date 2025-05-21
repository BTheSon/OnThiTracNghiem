// Lấy form
const form = document.getElementById('create-class-form');
const messageDiv = document.getElementById('message');

// Chặn sự kiện submit mặc định
form.addEventListener('submit', function(event) {
    event.preventDefault(); // Ngăn form submit theo cách thông thường

    // Thu thập dữ liệu từ form
    const formData = new FormData(form);
    const data = {
        malop: formData.get('malop'),
        tenlop: formData.get('tenlop'),
        mota: formData.get('mota')
    };

    // Gửi yêu cầu AJAX bằng fetch
    fetch('classroom/create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',// cho biết kiểu dữ liệu gửi lên và ánh xạ data vào $_POST
            'Accept': 'application/json',// nhập kiểu json từ server
        },
        body: new URLSearchParams(data) // Chuyển đổi dữ liệu thành định dạng URL query string
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Mạng hoặc server gặp sự cố: ' + response.status);
        }
        return response.json();
    })
    .then(result => {
        // Xử lý phản hồi từ server
        if (result.success) {
            messageDiv.innerHTML = '<p style="color: green;">Tạo lớp học thành công!</p>';
            form.reset(); // Reset form sau khi tạo thành công
        } else {
            messageDiv.innerHTML = '<p style="color: red;">Lỗi: ' + result.message + '</p>';
        }
    })
    .catch(error => {
        console.error('Lỗi:', error);
        messageDiv.innerHTML = '<p style="color: red;">Đã có lỗi xảy ra. Vui lòng thử lại.</p>';
    });
});