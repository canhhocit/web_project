const openBtn = document.querySelector('.open-modal-btn');
const modal = document.querySelector('.modal');
const closeIcon = document.querySelector('.close');
const closeBtn = document.querySelector('.close-btn');

// Mở modal
openBtn.addEventListener('click', () => {
    modal.classList.add('active');
});

// Đóng modal (click X)
closeIcon.addEventListener('click', () => {
    modal.classList.remove('active');
});

// Đóng modal (click nút Đóng)
closeBtn.addEventListener('click', () => {
    modal.classList.remove('active');
});

// // Click ra ngoài modal để đóng
// modal.addEventListener('click', (e) => {
//     if (e.target === modal) {
//         modal.classList.remove('active');
//     }
// });
