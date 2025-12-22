<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Form</title>
    <link rel="stylesheet" href="../CSS/taikhoan/login.css" />
  </head>

  <body>
    <form action="/web_project/index.php?controller=taikhoan&action=checklogin" method="post" id="loginForm">
    <div id="box">
      <h2>Đăng nhập</h2>
        <input type="text" id="username" name="username" placeholder="Username" required />
        <input type="password" id="password" name="password" placeholder="Password" required />
        
        <button id="btnLogin" type="submit">Đăng nhập</button><br />
        <i class="register">Bạn chưa có tài khoản?<a href="register.php">Đăng ký</a></i>
      </div>
    </form>
  </body>
  <script src="../JS/taikhoan/login.js"></script>
</html>
