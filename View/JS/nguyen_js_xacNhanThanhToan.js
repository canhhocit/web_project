// const buttons = document.querySelectorAll(
//     ".div_button_xacnhanthanhtoan button"
// );

// buttons[0].addEventListener("click", () => {
//     alert("Bạn đã hủy thanh toán.");
// });

// buttons[1].addEventListener("click", () => {
//     alert("Thanh toán thành công!");
// });
const openModalBtn = document.getElementById("openModal");
const btnhuy = document.getElementById("btnhuy");

const modalContainer = document.querySelector(".container_xacnhanthanhtoan");
openModalBtn.addEventListener("click", () => {
    modalContainer.classList.add("active");
});

btnhuy.addEventListener("click", () => {
    modalContainer.classList.remove("active");
});
