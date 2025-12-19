const togglePassword = document.getElementById("togglePassword");
const passwordInput = document.getElementById("password");

let isShow = false;

togglePassword.addEventListener("click", function () {
  isShow = !isShow;

  passwordInput.type = isShow ? "text" : "password";

  togglePassword.src = isShow
    ? "https://cdn-icons-png.flaticon.com/128/8395/8395688.png" // mở mắt
    : "https://cdn-icons-png.flaticon.com/128/7794/7794218.png"; // nhắm mắt
});

