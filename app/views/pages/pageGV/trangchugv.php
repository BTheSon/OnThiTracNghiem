<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?=BASE_URL?>/">
    <link rel="stylesheet" type="text/css" href="public/css/trangchugv.css"></head>
    <title>Giáo Viên</title>
</head>
<body>
    <header>
        <div class="menu-icon">☰</div>
        <div class="title">QUIZ</div>
        <div class="user-profile">
            <div class="user-info">
                <div class="user-title">Title</div>
                <div class="user-description">Description</div>
            </div>
            <div class="avatar"></div>
        </div>
    </header>
    <div class="side-menu">
        <div class="menu-item">
            <div class="menu-item-icon">⌂</div>
            <div>Màn hình chính</div>
        </div>
        <div class="menu-item">
            <div class="menu-item-icon">☐</div>
            <div>Bài tập</div>
        </div>
        <div class="menu-item">
            <div class="menu-item-icon">🔔</div>
            <div>Thông báo</div>
            <div style="margin-left: auto">▼</div>
        </div>
        <div class="menu-item">
            <div class="menu-item-icon">📁</div>
            <div>Đã đăng ký</div>
            <div style="margin-left: auto">▼</div>
        </div>
        <div class="menu-item active">
            <div class="menu-item-icon">
                <div style="width: 24px; height: 24px; background: #ccc; border-radius: 50%;"></div>
            </div>
            <div>Giải tích</div>
        </div>
        <div style="position: absolute; bottom: 15px; width: 100%;">
            <div class="menu-item">
                <div class="menu-item-icon settings-icon">⚙</div>
                <div>Cài đặt</div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="section-title">Lớp học:</div>
        <div class="class-cards">
            <div class="card">
                <div class="card-header">Giải Tích</div>
                <div class="card-avatar"></div>
            </div>
            <div class="card">
                <div class="card-header">Giải Tích</div>
                <div class="card-avatar"></div>
            </div>
        </div>
    </div>

    <div class="floating-button">+</div>

    <footer>
        <div class="footer-links">
            <a href="#" class="footer-link">Về chúng tôi</a>
            <a href="#" class="footer-link">Điều khoản sử dụng</a>
            <a href="#" class="footer-link">Chính sách bảo mật</a>
            <a href="#" class="footer-link">Liên hệ</a>
        </div>
        <div>© 2025 QUIZ. Tất cả các quyền được bảo lưu.</div>
    </footer>
</body>
</html>