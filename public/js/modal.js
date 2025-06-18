
let currentEditingClassId = null;

function toggleMenu(event, id) {
    event.stopPropagation();
    event.preventDefault();
    document.querySelectorAll('.menu-options').forEach(menu => menu.style.display = 'none');
    const menu = document.getElementById(`menu-${id}`);
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
}

function cardClicked(event) {
    const target = event.target;
    if (
        target.closest('.menu-button') ||
        target.closest('.menu-options') ||
        target.tagName === 'BUTTON' ||
        target.tagName === 'I'
    ) {
        event.preventDefault();
    }
}

function showEditModal(classId) {
    currentEditingClassId = classId;
    const mota = document.getElementById(`mota-${classId}`).textContent;
    const header = document.getElementById(`header-${classId}`).textContent;
    document.getElementById('edit-mota').value = mota.trim();
    document.getElementById('edit-header').value = header.trim();
    document.getElementById('edit-modal').style.display = 'block';
    document.getElementById(`menu-${classId}`).style.display = 'none';
}

function closeEditModal() {
    document.getElementById('edit-modal').style.display = 'none';
    currentEditingClassId = null;
}

function saveEdit() {
    const mota = document.getElementById('edit-mota').value.trim();
    const header = document.getElementById('edit-header').value.trim();

    if (!mota || !header) {
        alert('Vui lòng nhập đầy đủ thông tin!');
        return;
    }

    const formData = new FormData();
    formData.append('mo_ta', mota);
    formData.append('ten_lop', header);
    console.log(formData);
    fetch(`classroom/update/${currentEditingClassId}`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message + ' Đã xảy ra lỗi!');
        }
    })
    .catch(err => {
        alert('Lỗi khi gửi yêu cầu cập nhật');
        console.error(err);
    });
}


function confirmDelete(event, classId) {
    event.preventDefault();
    event.stopPropagation();
    if (confirm('Bạn có chắc chắn muốn xóa lớp này?')) {
        window.location.href = `classroom/delete/${classId}`;
    }
}

// Ẩn menu khi click ra ngoài
document.addEventListener('click', () => {
    document.querySelectorAll('.menu-options').forEach(menu => menu.style.display = 'none');
});

// Đóng modal khi click ra ngoài
window.onclick = function(event) {
    const modal = document.getElementById('edit-modal');
    if (event.target === modal) {
        closeEditModal();
    }
};
