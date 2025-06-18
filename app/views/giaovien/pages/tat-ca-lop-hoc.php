
<style>
            .menu-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: var(--primary-color);
        }

        .menu-options {
            position: absolute;
            top: 40px;
            right: 10px;
            background: white;
            border: 1px solid var(--primary-color);
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            display: none;
            z-index: 10;
        }

        .menu-options button {
            display: block;
            width: 100%;
            padding: 8px 16px;
            background: none;
            border: none;
            text-align: left;
            cursor: pointer;
            color: var(--primary-color);
            font-weight: 500;
        }

        .menu-options button:hover {
            background-color: #e3f2fd;
        }
        /* ===== Modal sửa ===== */
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            inset: 0;
            width: 100vw;
            height: 100vh;
            overflow: auto;
            
            box-shadow: 0 0 0 100vw rgb(179, 224, 255);
        }

        .modal-content {
            box-sizing: border-box;      /* Tính cả padding/border vào width */
            width: 320px;                /* hoặc width phù hợp */
            padding: 20px;
            margin: 10% auto;
            background-color: #fff;
            border: 2px solid var(--primary-color);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgb(179, 224, 255);
        }

        .modal-content h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        .modal-content label {
            font-weight: 500;
            margin-bottom: 4px;
            display: block;
            color: #444;
        }
        .modal-content input {
            box-sizing: border-box;      /* Ngăn tràn input */
            width: 100%;                 /* Chiếm 100% container đã bao gồm padding */
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .modal-content button {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .modal-content button:first-child {
            background-color: var(--primary-color);
            color: white;
        }

        .modal-content button:first-child:hover {
            background-color: var(--primary-hover);
        }

        .modal-content button:last-child {
            background-color: #e0e0e0;
            color: #333;
        }

        .modal-content button:last-child:hover {
            background-color: #ccc;
        }
</style>
<div class="content">
    <div class="section-title">Lớp học:</div>
    <div class="class-cards">
    <?php foreach($data['info_classes'] as $classes): ?>
        <a href="teacher/class-management/<?=$classes['id']?>" class="card" id="card-<?=$classes['id']?>" onclick="cardClicked(event)">
            <div class="card-mota" id="mota-<?=$classes['id']?>"><?=$classes['mo_ta']?></div>
            <div class="card-header" id="header-<?=$classes['id']?>"><?=$classes['ten_lop']?></div>

            <!-- Nút 3 chấm -->
            <button class="menu-button" onclick="toggleMenu(event, <?=$classes['id']?>)">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </button>

            <!-- Menu tùy chọn -->
            <div class="menu-options" id="menu-<?=$classes['id']?>">
                <button onclick="showEditModal(<?=$classes['id']?>); event.stopPropagation(); event.preventDefault();">Sửa</button>
                <button onclick="confirmDelete(event, <?=$classes['id']?>)">Xóa</button>
            </div>
        </a>
    <?php endforeach;?>
    <!-- Modal sửa -->
    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <h3>Chỉnh sửa lớp học</h3>
            <label for="edit-header">Tên lớp học</label>
            <input type="text" id="edit-header" placeholder="Tên lớp học">
            <label for="edit-mota">Mô tả lớp học</label>
            <input type="text" id="edit-mota" placeholder="Mô tả lớp học">
            <button onclick="saveEdit()">Lưu</button>
            <button onclick="closeEditModal()">Hủy</button>
        </div>
    </div>

</div>

</div>  
<script>
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

</script>