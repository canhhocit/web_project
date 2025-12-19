<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Register</title>
  </head>
  <link rel="stylesheet" href="../CSS/register.css" />

  <body>
    <div class="header">
      <h1>Register</h1> <br>
    </div>
    <form action="/web_project/index.php?controller=taikhoan&action=add" method="post" id="registerForm" >
      <label>Username:</label><br />
      <input type="text" id="username" name="username"required /><br />

      <label>Password:</label><br />
      <input type="password" id="password" name="password" required/><br />

      <label>Confirm Password:</label><br />
      <input type="password" id="confpassword" name="confpassword" required
      /><br />

      <div class="btn">
        <a href="login.php" id="btnBack">Back</a>
        <button type="submit" id="btnRegister">Register</button>
      </div>
    </form>
  </body>
  <script src="../JS/register.js"></script>
</html>
