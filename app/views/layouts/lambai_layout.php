<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?=BASE_URL?>/"> 
    <title>Trang Thi</title>
    <script defer src="https://unpkg.com/mathlive@0.98.6/dist/mathlive.min.js"></script> <!--tải thư viện hiển thị công thức toán    -->
    <?=$data['css_file']?>
</head>
<body>
    <main>
        <?=$content?>
    </main>
    <?=$data['js_file']?>
</body>
</html>