const formRegister = document.getElementById("registerForm");
const btnRegister = document.getElementById("btnRegister");
const btnBack = document.getElementById("btnBack");
const msg = document.getElementById("msg");

formRegister.addEventListener("submit", function (e) {
  e.preventDefault();
  const username = document.getElementById("username").value.trim();
  const password = document.getElementById("password").value.trim();
  const confpassword = document.getElementById("confpassword").value.trim();

  if (username === "" || password === "" || confpassword === "") {
    msg.innerHTML = "Vui lòng nhập đầy đủ thông tin!";
    return;
  }
  if (username.length < 4) {
    msg.innerHTML = "Username phải ít nhất 4 ký tự";
    return;
  }

  if (password.length < 6) {
    msg.innerHTML = "Password phải ít nhất 6 ký tự";
    return;
  }
  if (password !== confpassword) {
    msg.innerHTML = "Mật khẩu không khớp!";
    return;
  }

  btnRegister.disabled = true;
  btnBack.style.pointerEvents = "none"; //  link
  msg.style.color = " #fdfdfdff";
  let time = 10;
  const countdown = setInterval(() => {
    msg.innerHTML = `<span class="spinner"></span> Đang xử lý, vui lòng chờ trong ${time}s...`;

    if (time <= 0) {
      clearInterval(countdown);
      formRegister.submit();
    }
    time--;
  }, 1000);
});
