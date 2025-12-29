<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quên Mật Khẩu</title>
    <link rel="stylesheet" href="../CSS/taikhoan/forgot.css" />
</head>

<body>
    <div id="box">
        <h2>Quên Mật Khẩu</h2>

        <form action="/web_project/index.php?controller=taikhoan&action=forgot" method="post">
            <p style="text-align: center; color: #666; font-size: 14px;">
                Nhập username để lấy lại mật khẩu
            </p>

            <input type="text" name="username" placeholder="Nhập Username của bạn" required />
            <button type="submit" name="check_username">Tiếp tục</button>

            <div class="back-login">
                <a href="login.php">Quay lại đăng nhập</a>
            </div>
        </form>
    </div>
</body>

</html>