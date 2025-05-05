<!DOCTYPE html>
<html lang="en">
<head>
    <base href="">
    <meta charset="UTF-8">
    
    <title>Document</title>
</head>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header {
            background-color: #b3e5fc;
            height: 60px;
            width: 100%;
        }

        .container {
            display: flex;
            flex: 1;
        }

        .sidebar {
            background-color: #f44336;
            width: 200px;
            min-height: calc(100vh - 120px);
        }

        .main-content {
            background-color: #ffffff;
            flex: 1;
            min-height: calc(100vh - 120px);
        }

        .footer {
            background-color: #212121;
            height: 60px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="header"></div>
    <div class="container">
        <div class="sidebar"></div>
        <div class="main-content"></div>
    </div>
    <div class="footer"></div>
</body>
</html>