document.querySelectorAll('.delete-exam').forEach(function(elem) {
        elem.addEventListener('click', function(e) {
            e.preventDefault();
            d = this;
            deleteProccess(this);
        });
    });

    function deleteProccess(d) {
        if (!confirm('Bạn có chắc muốn xóa?')) {
            return
        }
        const api = d.getAttribute('data-url');
        alert(api);
        fetch(api, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Xử lý dữ liệu trả về từ server (data)
            console.log(data);
            // Ví dụ: reload lại trang nếu xóa thành công
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Xóa thất bại: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
            alert('Đã xảy ra lỗi khi xóa.');
        });
    }