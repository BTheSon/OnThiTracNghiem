function hideForm() {
    document.getElementById('formOverlay').style.display = 'none';
}

function showForm() {
    document.getElementById('formOverlay').style.display = 'flex';
}

function loadForm(url) {
    // tải html từ url bàng feactch
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            // chèn html vào div có id là form-container
            var contentForm = document.getElementById('form-content');
            contentForm.innerHTML = html;
            showForm();
        })
        .catch(error => {
            console.error('Error loading form:', error);
        });
}


// bắt sự kiện submit của trang web và kiểm tra có tồn tại thẻ form có id #uploadForm không
// nếu có thì thực thi scpript để xử lý submit của form đó
document.addEventListener('submit', function(e) {
    if (e.target.matches('#uploadForm')) {
        e.preventDefault();
        const formData = new FormData(e.target);
        console.log('Form data:', formData);
        fetch('document/upload', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                alert(data.message);
            })
            .catch(err => {
                console.error('Lỗi upload:', err);
            });
    }
});


console.log('load-form.js loaded');
