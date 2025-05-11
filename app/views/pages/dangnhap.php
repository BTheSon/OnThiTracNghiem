<?php
use function App\Includes\asset;

?>
<html>
<head>
    <title>Đăng nhập</title>
    <base href="<?=BASE_URL?>/public/css/">
    <link rel="stylesheet" type="text/css" href="cssDangNhap.css"></head>
<body>
    <div class="container">
        <div class="heading">Đăng nhập</div>
        <form action="" class="form" method="POST">
            <input required="" class="input" type="email" name="email" id="email" placeholder="E-mail">
            <input required="" class="input" type="password" name="password" id="password" placeholder="Password">
            <span class="forgot-password"><a href="#">Quên mật khẩu</a></span>
            <input class="login-button" type="submit" name="submit" value="Đăng nhập">  
        </form>
    </div>
</body>
</html>
