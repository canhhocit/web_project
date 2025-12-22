const openBtns = document.querySelectorAll(".open-modal-btn");
const closeBtns = document.querySelectorAll(".close-btn");

// open
openBtns.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    e.preventDefault();
    const modalId = btn.getAttribute("data-modal");
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.add("active");
    }
  });
});


closeBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    const modal = btn.closest(".modal");
    if (modal) {
      let oldpass = document.getElementById("oldpass").value.trim();
      let newpass = document.getElementById("newpass").value.trim();
      let confpass = document.getElementById("confnewpass").value.trim();
      let hoten = document.getElementById("hoten").value.trim();
      let sdt = document.getElementById("sdt").value.trim();
      let cccd = document.getElementById("cccd").value.trim();
      let email = document.getElementById("email").value.trim();

      if (oldpass !== "" || newpass !== "" || confpass !== ""||hoten!==""||sdt!==""||cccd!==""||email!=="") {
        if (confirm("Dữ liệu sẽ mất khi bạn chưa lưu. Bạn có chắc muốn đóng?")) {
          modal.classList.remove("active");
        }
        return; 
      }
      modal.classList.remove("active");
    }
  });
});
//  click  ngoài
document.querySelectorAll(".modal").forEach((modal) => {
  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      let oldpass = document.getElementById("oldpass").value.trim();
      let newpass = document.getElementById("newpass").value.trim();
      let confpass = document.getElementById("confnewpass").value.trim();
      let hoten = document.getElementById("hoten").value.trim();
      let sdt = document.getElementById("sdt").value.trim();
      let cccd = document.getElementById("cccd").value.trim();
      let email = document.getElementById("email").value.trim();

      if (oldpass !== "" || newpass !== "" || confpass !== ""||hoten!==""||sdt!==""||cccd!==""||email!=="") {
        if (confirm("Dữ liệu sẽ mất khi bạn chưa lưu. Bạn có chắc muốn đóng?")) {
          modal.classList.remove("active");
        }
        return; 
      }
      modal.classList.remove("active");
    }
  });
});
