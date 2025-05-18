<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ giáo viên</title>
    <base href="<?=BASE_URL?>/">    
    <link rel="stylesheet" href="app/public/css/main.css">
    <link rel="stylesheet" href="app/public/css/giaovien.css">
    <link rel="stylesheet" href="app/public/css/formgiaovien.css">
    </head>
<body>
    <?php 
    include_once 'app/includes/header.php';
    ?>
  
    <?php
    include_once 'app/views/page/giaovien/menu.php';
    ?>
    <!-- đây dùng để chuyển trang giữa các menu -->
      <div id="main-content" >
      <!-- Nội dung sẽ load vào đây -->


    </div>
           <script src="app/public/js/menuload.js"></script>
</body>

</html>