const modal = document.getElementById("modalOverlay");
const openBtn = document.getElementById("openModal");
const closeBtn = document.getElementById("closeModal");

openBtn.onclick = () => {
    modal.classList.add("active");
    document.body.style.overflow = "hidden"; // khóa scroll nền
};

closeBtn.onclick = () => {
    modal.classList.remove("active");
    document.body.style.overflow = "auto";
};

modal.onclick = (e) => {
    if (e.target === modal) {
        modal.classList.remove("active");
        document.body.style.overflow = "auto";
    }
};
