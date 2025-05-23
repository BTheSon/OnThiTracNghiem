function showForm() {
    document.getElementById('formOverlay').style.display = 'flex';
    document.getElementById('errorMessage').style.display = 'none'; // Ẩn thông báo lỗi khi mở form
}

function hideForm() {
    document.getElementById('formOverlay').style.display = 'none';
    document.getElementById('tieu_de').value = '';
    document.getElementById('mo_ta').value = '';// Reset input
    document.getElementById('errorMessage').style.display = 'none'; // Ẩn thông báo lỗi
}

function addTaiLieu() {

    var file = document.getElementById('file').value;
    var tieu_de = document.getElementById('tieu_de').value;
    var mo_ta = document.getElementById('mo_ta').value;
    var errorMessage = document.getElementById('errorMessage');

    if (tieu_de === '' || mo_ta === '' || file === '') {
        errorMessage.textContent = 'Vui lòng điền đầy đủ thông tin.';
        errorMessage.style.display = 'block';
        return;
    }

    // Gửi dữ liệu đến server
    fetch('Classroom/addTaiLieu', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json',
        },
        body: new URLSearchParams(
            {
                tieu_de: tieu_de,
                mo_ta: mo_ta
            }
        )

    })
}