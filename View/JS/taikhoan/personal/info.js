document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatar-input');
    const avatarBox = document.querySelector('.avatar-box');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const imgElement = avatarBox.querySelector('img');
                    
                    if (imgElement) {
                        // Nếu đã có img, chỉ cần thay đổi src
                        imgElement.src = e.target.result;
                    } else {
                        // Nếu chưa có img (đang là placeholder), tạo mới
                        avatarBox.innerHTML = '<img src="' + e.target.result + '" alt="Avatar" id="avatar-preview">';
                    }
                };
                
                reader.readAsDataURL(file);
            }
        });
    }
});