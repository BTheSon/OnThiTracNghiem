<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ học sinh</title>
    <base href="<?=BASE_URL?>/">    
    <link rel="stylesheet" href="app/public/css/main.css">
    <link rel="stylesheet" href="app/public/css/hocsinh.css">

    </head>
<body>
    <?php 
    include_once 'app/includes/header.php';
    ?>
  
    <?php
    include_once 'app/views/page/student/menu.php';
    ?>
    <!-- đây dùng để chuyển trang giữa các menu -->
      <div id="main-content" >
      <!-- Nội dung sẽ load vào đây -->
              <script src="app/public/js/menuload.js"></script>

    </div>
     
</body>

</html>