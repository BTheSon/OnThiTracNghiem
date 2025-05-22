
console.log('form-join-class.js loaded');
function showForm() {
    document.getElementById('formOverlay').style.display = 'flex';
    document.getElementById('errorMessage').style.display = 'none'; // Ẩn thông báo lỗi khi mở form
}

function hideForm() {
    document.getElementById('formOverlay').style.display = 'none';
    document.getElementById('classCode').value = ''; // Reset input
    document.getElementById('errorMessage').style.display = 'none'; // Ẩn thông báo lỗi
}

async function joinClass() {
    const classCode = document.getElementById('classCode').value;
    const errorMessage = document.getElementById('errorMessage');

    if (!classCode) {
        errorMessage.textContent = 'Vui lòng nhập mã lớp!';
        errorMessage.style.display = 'block';
        return;
    }

    fetch('classroom/join', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',// cho biết kiểu dữ liệu gửi lên và ánh xạ data vào $_POST
            'Accept': 'application/json',// nhập kiểu json từ server
        },
        body: new URLSearchParams({'malop' : classCode}) // Chuyển đổi dữ liệu thành định dạng URL query string
    })
    .then(function(response) {
        return response.json().then(function(data) {
            return { ok: response.ok, data: data };
        });
    })
    .then(function(result) {
        if (result.ok && result.data.success) {
            alert(result.data.message || 'Tham gia lớp thành công!');
            window.location.reload();
            hideForm();
        } else {
            errorMessage.textContent = result.data.message || 'Có lỗi xảy ra!';
            errorMessage.style.display = 'block';
        }
    })
    .catch(function(error) {
        errorMessage.textContent = 'Lỗi kết nối server!';
        errorMessage.style.display = 'block';
    });
}
