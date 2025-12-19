<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Form</title>
    <link rel="stylesheet" href="../CSS/login.css" />
  </head>

  <body>
    <form action="/web_project/index.php?controller=taikhoan&action=checklogin" method="post" id="loginForm">
    <div id="box">
      <h2>Đăng nhập</h2>
        <input type="text" id="username" name="username" placeholder="Username" required />
        <div class="password-box">
          <input type="password" id="password" name="password" placeholder="Password" required />
          <img
            src="https://cdn-icons-png.flaticon.com/128/7794/7794218.png" id="togglePassword"alt="toggle password"
          />
        </div>
        <button id="btnLogin" type="submit">Đăng nhập</button><br />
        <i class="register">Bạn chưa có tài khoản?<a href="register.php">Đăng ký</a></i>
      </div>
    </form>
  </body>
  <script src="../JS/login.js"></script>
</html>
