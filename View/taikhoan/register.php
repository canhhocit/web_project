<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Register</title>
</head>
<link rel="stylesheet" href="../CSS/taikhoan/register.css" />

<body>

  <div id="box">
    <h2>Register</h2> 
    <form action="/web_project/index.php?controller=taikhoan&action=add" method="post" id="registerForm">

      <input type="text" id="username" name="username" placeholder="Username" required /><br />

      <input type="password" id="password" name="password" placeholder="Password" /><br />

      <input type="password" id="confpassword" name="confpassword" placeholder="Confirm Password" required /><br>
      <div class="message">
        <i><span id="msg"></span></i>
      </div>
      <div class="btn">
        <button id="btnBack" onclick="history.back()">Back</button>
        <button type="submit" id="btnRegister">Register</button>
      </div>
    </form>
  </div>
</body>
<script src="../JS/taikhoan/register.js"></script>

</html>