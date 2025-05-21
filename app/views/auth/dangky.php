<html>
    <head>
    <title>Đăng ký</title>
    <base href="<?=BASE_URL?>/">
    <link rel="stylesheet" type="text/css" href="public/css/cssDangky.css">
    </head>
    <body>
        <div class="container">
            <form action="Auth/register" class="form" method ="post" id="form">
                <div class="heading">Đăng ký</div>
                <div class="error-message">
                    <?php
                        if (isset($data['error'])) {
                            echo $data['error'];
                        }
                    ?>
                </div>
                <input required="" class="input" type="text" name="name" id="name" placeholder="Họ và tên">
                <input required="" class="input" type="email" name="email" id="email" placeholder="E-mail">
                <input required="" class="input" type="password" name="password" id="password" placeholder="Password">
                <input required="" class="input" type="password" name="repassword" id="repassword" placeholder="Nhập lại password">
                <div class="role-select">
                    <label>
                        <input type="radio" name="role" value="giaovien" required>
                        Giáo viên
                    </label>
                    <label>
                        <input type="radio" name="role" value="hocsinh" required>
                        Học sinh
                    </label>
                </div>
                <input class="login-button" type="submit" name="submit" value="Đăng ký">
            </form>
            <i style="margin-top: 20px; color: #666;">Đã có tài khoản?  <a href="auth/login">Đăng nhập</a></i>
        </div>
    </body>
</html>