
<html>
    <head>
    <title>Đăng ký</title>
    <base href="<?=BASE_URL?>/public/css/">
    <link rel="stylesheet" type="text/css" href="cssDangky.css">
    </head>
    <body>
        <div class="container">
            <form action="<?=BASE_URL . ""?>" class="form" method ="post" id="form">
                <div class="heading">Đăng ký</div>
                <input required="" class="input" type="text" name="name" id="name" placeholder="Họ và tên">
                <input required="" class="input" type="email" name="email" id="email" placeholder="E-mail">
                <input required="" class="input" type="password" name="password" id="password" placeholder="Password">
                <input required="" class="input" type="password" name="repassword" id="repassword" placeholder="Nhập lại password">
                <input class="login-button" type="submit" name="submit" value="Đăng ký">
            </form>
            <span class="register-link">Đã có tài khoản? <a href= "<?=SUB_DIR_NAME?>/home/dangnhap">Đăng nhập</a></span>
        </div>
    </body>
</html>