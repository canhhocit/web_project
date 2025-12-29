// Validate form đổi mật khẩu
document.getElementById('resetPasswordForm')?.addEventListener('submit', function(e) {
    var newPass = document.getElementById('new_password').value;
    var confirmPass = document.getElementById('confirm_password').value;
    var captcha = grecaptcha.getResponse();
    
    var errorNew = document.getElementById('error_new');
    var errorConfirm = document.getElementById('error_confirm');
    var errorCaptcha = document.getElementById('error_captcha');
    
    errorNew.textContent = '';
    errorConfirm.textContent = '';
    errorCaptcha.textContent = '';
    
    var isValid = true;
    
    // if (newPass.length < 6) {
    //     errorNew.textContent = 'Mật khẩu phải có ít nhất 6 ký tự';
    //     isValid = false;
    // }
    
    if (newPass !== confirmPass) {
        errorConfirm.textContent = 'Mật khẩu xác nhận không khớp';
        isValid = false;
    }
    
    if (captcha === '') {
        errorCaptcha.textContent = 'Bạn không phải con ng';
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
    }
});