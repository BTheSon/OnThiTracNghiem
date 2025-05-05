<!DOCTYPE html>
<html lang="vi">
<head>
    <base href="">
    <meta charset="UTF-8">
    <title>Ôn Thi Trắc nghiệm</title>
</head>
<body>
    <div class="header">
        <?php require_once BASE_DIR . "/app/views/layouts/partials/header.php"?>
    </div>
    <div class="container">
        <div class="sidebar">

        </div>
        <div class="main-content">
            <?=$content?>
        </div>
    </div>
    <div class="footer">
        <?php require_once BASE_DIR . "/app/views/layouts/partials/footer.php"?>
    </div>
</body>
</html>