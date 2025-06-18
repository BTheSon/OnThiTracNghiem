<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Card + Popup sửa (Xanh nước)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --primary-color: #007BFF;
            --primary-hover: #0069d9;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
        }

        .card {
            position: relative;
            width: 300px;
            padding: 20px;
            background: #ffffff;
            border: 2px solid var(--primary-color);
            border-radius: 12px;
            text-decoration: none;
            color: inherit;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            display: block;
            margin: 50px auto;
        }

        .card-mota {
            font-size: 14px;
            color: #555;
        }

        .card-header {
            font-size: 18px;
            font-weight: bold;
            color: var(--primary-color);
            margin-top: 5px;
        }

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
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border: 2px solid var(--primary-color);
            width: 320px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .modal-content h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .modal-content input {
            width: 100%;
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
</head>

<body>

    <!-- Card -->
    <div class="card" id="card-1">
        <a href="teacher/class-management/1" style="text-decoration: none; color: inherit;">
            <div class="card-mota" id="mota-1">Toán lớp 12a1</div>
            <div class="card-header" id="header-1">Toán</div>
        </a>

        <!-- Nút 3 chấm -->
        <button class="menu-button" onclick="toggleMenu(event, 1)">
            <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>

        <!-- Menu tùy chọn -->
        <div class="menu-options" id="menu-1">
            <button onclick="showEditModal(1)">Sửa</button>
            <button onclick="confirmDelete(event, 1)">Xóa</button>
        </div>
    </div>

    <!-- Modal sửa -->
    <div id="edit-modal-1" class="modal">
        <div class="modal-content">
            <h3>Chỉnh sửa lớp học</h3>
            <input type="text" id="edit-mota-1" value="Toán lớp 12a1">
            <input type="text" id="edit-header-1" value="Toán">
            <button onclick="saveEdit(1)">Lưu</button>
            <button onclick="closeEditModal(1)">Hủy</button>
        </div>
    </div>

    <script>
        function toggleMenu(event, id) {
            event.stopPropagation();
            document.querySelectorAll('.menu-options').forEach(menu => menu.style.display = 'none');
            const menu = document.getElementById(`menu-${id}`);
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        }

        function showEditModal(id) {
            document.getElementById(`edit-modal-${id}`).style.display = 'block';
            document.getElementById(`menu-${id}`).style.display = 'none';
        }

        function closeEditModal(id) {
            document.getElementById(`edit-modal-${id}`).style.display = 'none';
        }

        function saveEdit(id) {
            const newMota = document.getElementById(`edit-mota-${id}`).value;
            const newHeader = document.getElementById(`edit-header-${id}`).value;
            document.getElementById(`mota-${id}`).textContent = newMota;
            document.getElementById(`header-${id}`).textContent = newHeader;
            closeEditModal(id);
        }

        function confirmDelete(event, id) {
            event.stopPropagation();
            if (confirm("Bạn có chắc muốn xóa lớp này không?")) {
                alert("Đã xóa lớp có ID: " + id); // xử lý xóa thật ở đây
            }
        }

        // Ẩn menu khi click ra ngoài
        document.addEventListener('click', () => {
            document.querySelectorAll('.menu-options').forEach(menu => menu.style.display = 'none');
        });

        // Ẩn modal khi click ra ngoài vùng form
        window.onclick = function(event) {
            document.querySelectorAll('.modal').forEach(modal => {
                if (event.target === modal) modal.style.display = "none";
            });
        };
    </script>

</body>

</html>