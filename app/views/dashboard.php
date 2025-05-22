<?php

use function App\Includes\asset;
?>
<html>
<head>
    <title>Ôn tập và thi</title>
    <base href="<?=BASE_URL?>/">
    <link rel="stylesheet" type="text/css" href="public/css/style.css"></head>
<body>
    <header class="header">
        <div class="logo">QUIZ</div>
        <nav class="nav">
            <a href="#">Trang chủ</a>
            <a href="#">Khám phá</a>
            <a href="#">Giới thiệu</a>
            <a href="Auth/login" id="loginBtn">Đăng nhập</a>
        </nav>
    </header>
    
    <section class="hero">
        <h1>Ôn tập và thi hiệu quả</h1>
        <p>Trải nghiệm phương pháp học tập tương tác với hàng ngàn bài tập, đề thi được cập nhật liên tục. Nâng cao kiến thức và đạt điểm số cao trong các kỳ thi.</p>
        <a href="Auth/login" class="cta-button">Bắt đầu ngay</a>
    </section>
    
    <section class="features">
        <div class="feature-card">
            <div class="feature-icon">📚</div>
            <h3>Kho đề phong phú</h3>
            <p>Hơn 10,000 câu hỏi và đề thi được biên soạn bởi đội ngũ giáo viên giàu kinh nghiệm, bao phủ mọi môn học và cấp độ.</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">⏱️</div>
            <h3>Thi thử thực tế</h3>
            <p>Mô phỏng môi trường thi thực tế với thời gian đếm ngược, giúp học sinh làm quen với áp lực trong phòng thi.</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">📊</div>
            <h3>Phân tích chi tiết</h3>
            <p>Hệ thống phân tích kết quả thông minh, chỉ ra điểm mạnh, điểm yếu và hướng dẫn cải thiện hiệu quả.</p>
        </div>
    </section>
    <section class="categories">
        <h2>Khám phá các danh mục</h2>
        
        <div class="category-grid">
            <div class="category-card">
                <div class="category-img">🧮</div>
                <div class="category-info">
                    <h3>Toán học</h3>
                    <p>Đại số, Hình học, Giải tích và nhiều hơn nữa</p>
                    <a href="#" class="category-link">Xem chi tiết »</a>
                </div>
            </div>
            
            <div class="category-card">
                <div class="category-img">🌎</div>
                <div class="category-info">
                    <h3>Tiếng Anh</h3>
                    <p>Ngữ pháp, Từ vựng, Đọc hiểu, Nghe nói</p>
                    <a href="#" class="category-link">Xem chi tiết »</a>
                </div>
            </div>
        </div>
    </section>
    
    <footer class="footer">
        <div class="footer-links">
            <a href="#">Về chúng tôi</a>
            <a href="#">Điều khoản sử dụng</a>
            <a href="#">Chính sách bảo mật</a>
            <a href="#">Liên hệ</a>
        </div>
        <p class="copyright">© 2025 QUIZ. Tất cả các quyền được bảo lưu.</p>
    </footer>
    
    
</body>
</html>