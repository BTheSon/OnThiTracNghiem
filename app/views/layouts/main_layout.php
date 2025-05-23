<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QUIZ</title>
    <base href="<?=BASE_URL?>/">    
    <link rel="stylesheet" href="public/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <?=$data['css_file']?>

</head>
<body>
    
    <div class="header">
        <div class="menu-icon" id = "toggle-menu">☰</div>
        <div class="title">QUIZ</div>
        <div class="user-profile">
            <div class="user-info">
                <div class="user-title"><?=$_SESSION['user_name']?></div>
                <div class="user-description"><?=$_SESSION['user_role']?></div>
            </div>
            <div class="avatar"></div>
            <button class="logout" id="logout-btn" onclick="window.location.href='auth/logout'">Đăng xuất</button>
        </div>
    </div>
    <div class="side-menu">
        <?=$sidebar?>
    </div>
    <!-- đây dùng để chuyển trang giữa các menu -->
    <div id="main-content" >
        <?=$content?>
    </div>
    <?=$data['js_file']?>
</body>

</html>