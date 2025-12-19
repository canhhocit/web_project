document
  .getElementById("registerForm")
  .addEventListener("submit", function (e) {
    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value.trim();
    const confpassword = document.getElementById("confpassword").value.trim();

    // Check rỗng
    if (username === "" || password === "" || confpassword === "") {
      alert("Vui lòng nhập đầy đủ thông tin!");
      e.preventDefault();
      return;
    }

    //   if (username.length < 4) {
    //     alert("Username phải ít nhất 4 ký tự");
    //     e.preventDefault();
    //     return;
    //   }

    //   if (password.length < 6) {
    //     alert("Password phải ít nhất 6 ký tự");
    //     e.preventDefault();
    //     return;
    //   }

    if (password !== confpassword) {
      alert("Mật khẩu xác nhận không khớp!");
      e.preventDefault();
      return;
    }
  });
