<?php
session_start();

if (!isset($_SESSION['reset_username'])) {
    echo "<script>
        alert('k ton tai ss!');
        window.location.href='forgot.php';
    </script>";
    exit;
}

$username = $_SESSION['reset_username'];
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đổi Mật Khẩu</title>
    <link rel="stylesheet" href="../CSS/taikhoan/forgot.css" />
    <script src="https://www.google.com/recaptcha/api.js"></script>
</head>

<body>
    <div id="box">
        <h2>Đổi Mật Khẩu</h2>

        <form action="/web_project/index.php?controller=taikhoan&action=resetPassword" method="post" id="resetPasswordForm">
            <p style="text-align: center; color: #666; font-size: 14px;">
                Nhập mật khẩu mới cho: <strong><?php echo $username; ?></strong>
            </p>

            <input type="password" name="new_password" id="new_password" placeholder="Mật khẩu mới" required />
            <div id="error_new" class="error"></div>

            <input type="password" name="confirm_password" id="confirm_password" placeholder="Xác nhận mật khẩu" required />
            <div id="error_confirm" class="error"></div>
            <div class="boxcapcha">
                <div class="g-recaptcha"
                    data-sitekey="6LfM2TksAAAAAIbL4PcQf3dHlFev8yxpD01SjlUt">
                </div>
            </div>
            <div id="error_captcha" class="error"></div>

            <button type="submit">Đổi mật khẩu</button>

            <div class="back-login">
                <a href="login.php">Quay lại đăng nhập</a>
            </div>
        </form>
    </div>

    <script src="../JS/taikhoan/forgot.js"></script>
</body>

</html>