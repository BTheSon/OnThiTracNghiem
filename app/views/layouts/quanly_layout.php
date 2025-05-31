<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QUIZ</title>
    <base href="<?=BASE_URL?>/">    
    <link rel="stylesheet" href="public/css/main.css">
    <link rel="stylesheet" href="public/css/quanly_layout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <?=$data['css_file']?>

</head>
<body>
    
    <div class="header">
        <?php require_once 'header.php';?>
    </div>
    <div class="side-menu">
        <?=$sidebar?>
    </div>
    <!-- đây dùng để chuyển trang giữa các menu -->
    <main id="main-content" >

        <div class="content-buttons">
            <?=$navbar?>
        </div>
        <div class="content-placeholder">
            <?=$content?>
        </div>
    </main>
    <?=$data['js_file']?>
</body>
</html>