const loginForm = document.getElementById("loginForm");
const btnLogin = document.getElementById("btnLogin");

loginForm.addEventListener("submit", (e) => {
    e.preventDefault(); 

    const user = document.getElementById("username").value.trim();
    const pass = document.getElementById("password").value.trim();

    if (user === "" || pass === "") {
        alert("Vui lòng nhập tài khoản và mật khẩu");
        return;
    }

    // const originalText = btnLogin.innerHTML;
    btnLogin.disabled = true;
    btnLogin.innerHTML = '<span class="spinner"></span> Đang xác thực...';

    setTimeout(() => {
        loginForm.submit(); 
    }, 1000);
});