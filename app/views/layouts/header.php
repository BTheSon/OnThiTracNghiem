<div class="title-header">
    Nền tảng ôn tập và kiểm tra trực tuyến
</div>

<div class="title">
    <i class='fa-solid'>QUIZ</i>    
</div>

<!-- Avatar -->
<div style="display:flex; flex-direction: row-reverse;">
    <?php
    if (empty($_SESSION['user_avt_url'])) {
        $avt_url = DEFAULT_AVT_URL;
    } else {   
        $avt_url = ltrim($_SESSION['user_avt_url'], '/');  // /storages/avt/img.png => storages/avt/img.png => 
    }
    ?>
    <div id="avatar" class="avatar" style="background-image: url('<?=$avt_url?>');"></div>
    <div class = 'troll'>
        <p>
            <strong>Username:</strong> 
            <span><?=$_SESSION['user_name']?></span>
        </p>
    </div>
</div>

<!-- Profile Popup -->
<div id="profilePopup" class="profile-popup">
    <p>
        <strong>Email:</strong> 
        <span id="username"><?=$_SESSION['user_email']?></span>
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
        