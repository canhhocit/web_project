document.addEventListener('DOMContentLoaded', function () {
    const avatarInput = document.getElementById('avatar-input');
    const avatarBox = document.querySelector('.avatar-box');

    if (!avatarInput || !avatarBox) return;

    avatarInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        let img = avatarBox.querySelector('img');

        if (!img) {
            img = document.createElement('img');
            img.id = 'avatar-preview';
            img.alt = 'Avatar';
            avatarBox.innerHTML = '';
            avatarBox.appendChild(img);
        }
        img.src = URL.createObjectURL(file);
    });
});
