<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QUIZ</title>
    <base href="<?=BASE_URL?>/">    
    <link rel="stylesheet" href="public/css/quanly_layout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <?=$data['css_file']?>

</head>
<body>
    
    <div class="header">
        <div class="menu-icon" id = "toggle-menu">☰</div>
        <div class="title">
            <i class='fa-solid'>QUIZ</i>    
        </div>

        <!-- Avatar -->
        <div style="display:flex; flex-direction: row-reverse;">
            <div id="avatar" class="avatar"></div>
            <div class = 'troll'>
                <p>
                    <strong>Email:</strong> 
                    <span><?=$_SESSION['user_email']?></span>
                </p>
            </div>
        </div>
        
        <!-- Profile Popup -->
        <div id="profilePopup" class="profile-popup">
            <p>
                <strong>Tên người dùng:</strong> 
                <span id="username"><?=$_SESSION['user_name']?></span>
            </p>
            <p>
                <strong>Vai trò:</strong> 
                <span id="email"><?=$_SESSION['user_role']?></span>
            </p>
            <button onclick="window.location.href = 'user/change-avatar'">Đổi ảnh đại diện</button>
            <button onclick="window.location.href = 'user/change-name'">Đổi tên</button>
            <button onclick = "window.location.href = 'auth/change-password'">Đổi mật khẩu</button>
            <button onclick="window.location.href = 'auth/logout'">Đăng xuất</button>
        </div>
        <script>
            const avatar = document.getElementById('avatar');
            const popup = document.getElementById('profilePopup');
            popup.style.display = 'none';
            avatar.addEventListener('click', function(e) {
                e.stopPropagation();
                popup.style.display = (popup.style.display === 'none' || popup.style.display === '') ? 'block' : 'none';
                // Hiển thị popup đổ xuống về phía bên trái avatar
                const rect = avatar.getBoundingClientRect();
                popup.style.position = 'absolute';
                popup.style.top = (rect.bottom + window.scrollY) + 'px';
                // Đặt popup sát bên trái avatar
                popup.style.left = (rect.left + window.scrollX - popup.offsetWidth + avatar.offsetWidth) + 'px';
            });
            document.addEventListener('click', function(e) {
            if (!popup.contains(e.target)) {
                popup.style.display = 'none';
            }
            });
        </script>
    </div>
    <div class="side-menu">
        <?=$sidebar?>
    </div>
    <!-- đây dùng để chuyển trang giữa các menu -->
    <main id="main-content" >

        <div class="content-buttons">
            <?=$navbar?>
        </div>
        <div class="content-placeholder">
            <?=$content?>
        </div>
    </main>
    <?=$data['js_file']?>
</body>
</html>