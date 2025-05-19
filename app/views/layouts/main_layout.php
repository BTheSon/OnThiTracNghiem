<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ học sinh</title>
    <base href="<?=BASE_URL?>/">    
    <link rel="stylesheet" href="public/css/main.css">
    <?=$data['css_file']?>

</head>
<body>
    
    <div class="header">
        <div class="menu-icon" id = "toggle-menu">☰</div>
        <div class="title">QUIZ</div>
        <div class="user-profile">
            <div class="user-info">
                <div class="user-title">Title</div>
                <div class="user-description">Description</div>
            </div>
            <div class="avatar"></div>
        </div>
    </div>
    <?=$sidebar?>
    <!-- đây dùng để chuyển trang giữa các menu -->
    <div id="main-content" >
        <?=$content?>
        <!-- <script src="public/js/menuload.js"></script> -->
    </div>
    
</body>

</html>