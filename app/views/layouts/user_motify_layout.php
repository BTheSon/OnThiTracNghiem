<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa thông tin người dùng</title>
    <base href="<?=BASE_URL?>/">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            background-color: #f8f9fa;
        }
        
    </style>
    <?=$data['css_file']?>
</head>
<body>
    <?php
    $url = $_SESSION['user_role'] === 'gv'? 'teacher' : 'student'
?>
<a href="<?=$url?>" 
    style="
        position: absolute; 
        top: 10px; 
        left: 10px; 
        text-decoration: none; 
        padding: 8px 16px; 
        background: #005f99; 
        color: #fff; 
        border-radius: 4px;">
    quay lại
</a>

    <main>
        <?=$content?>
    </main>
    <?=$data['js_file']?>
</body>
</html>