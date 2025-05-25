<?php
use function App\Includes\asset;

?>
<html>
<head>
    <title>Đăng nhập</title>
    <base href="<?=BASE_URL?>/">
    <link rel="stylesheet" type="text/css" href="public/css/cssDangNhap.css"></head>
<body>
    <div class="container">
        <div class="heading">Đăng nhập</div>
        <div class="error-message">
            <?php
                if (isset($data['error'])) {
                    echo $data['error'];
                }
            ?>
        <form action="" class="form" method="POST">
            <input class="input" type="email" name="email" id="email" placeholder="E-mail" required>
            <input class="input" type="password" name="password" id="password" placeholder="Password" required>
            <span class="forgot-password" require><a href="#">Quên mật khẩu</a></span>
            <input class="login-button" type="submit" name="submit" value="Đăng nhập">  
            <i style="margin-top: 20px; color: #666;">Chưa có tài khoản? <a href="auth/register">Bấm vào đây</a></i>
        </form>
    </div>
</body>
</html>
